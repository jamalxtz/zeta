<?php 
    /* Prepara o documento para comunicação com o JSON, as duas linhas a seguir são obrigatórias 
	  para que o PHP saiba que irá se comunicar com o JSON, elas sempre devem estar no ínicio da página */
	header("Cache-Control: no-cache, no-store, must-revalidate"); // Limpa o cache
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=utf-8"); 

	// Limpa o cache
	clearstatcache(); 
    

//******************* DECLARAÇÃO DE VARIÁVEIS *************************************************************************
    /*Recebe a requisição via POST e redireciona para o método responsável por tratar essa requisição
    *Para testar basta procar o _POST por _GET e utilizar o seguinte padrão de URL:
    *http://localhost:8090/zeta/zeta/models/finances/api-finances-model.php?requisicao=consultaSimples&outroParametro=22
    */
    if (isset($_POST['requisicao'])){
        $requisicao = $_POST['requisicao'];
    }elseif (isset($_GET['requisicao'])){
        $requisicao = $_GET['requisicao'];
    }else{
        $requisicao = "";
    }
    //echo($requisicao);

    date_default_timezone_set('America/Sao_Paulo');

    //Faz a instância da classe da API
    $financesAPI = new FinancesAPI();
    //Chama o método que distribui as requisições e passa como parâmetro as requisições recebidas via POST
    $financesAPI->DistribuirRequisicao($requisicao);

    class FinancesAPI {
        //Pega a data atual
        //public $dataAtual = date('Y/m/d H:i:s');
        
        /**
         * Recebe a requisição via post e chama o método reponsável por tratar aquela determinada requisição.
         */
        public function DistribuirRequisicao($requisicao){
            switch ($requisicao) {
                case "consultaSimples":
                    $this->ConsultaSimples();
                    break;
                case "criarBancoDeDados":
                    $this->CriarBancoDeDados();
                    break;
                case "incluirDespesa":
                    $this->IncluirDespesa();
                    break;
                case "incluirDespesaFixa":
                    $this->IncluirDespesaFixa();
                    break;
                case "incluirCategoria":
                    $this->IncluirCategoria();
                    break;
                case "listarDespesasMensal":
                    $this->ListarDespesasMensal();
                    break;
                case "quitarDespesa":
                    $this->QuitarDespesa();
                    break;
                case "estornarDespesa":
                    $this->EstornarDespesa();
                    break;
                case "listarDadosDespesaPendentePorCodigo":
                    $this->ListarDadosDespesaPendentePorCodigo();
                    break;
                case "incluirParcelaDespesa":
                    $this->IncluirParcelaDespesa(null,null);
                    break;
                case "alterarParcelaDespesa":
                    $this->AlterarParcelaDespesa();
                    break;
                case "excluirParcelaDespesa":
                    $this->ExcluirParcelaDespesa();
                    break;
                case "alterarDespesa":
                    $this->AlterarDespesa();
                    break;
                case "alterarDespesaFixa":
                    $this->AlterarDespesaFixa();
                    break;
                case "atualizarDataReferencia":
                    $this->AtualizarDataReferencia();
                    break;
                case "buscarDataReferencia":
                    $this->BuscarDataReferencia();
                    break;
                default:
                    $this->RetornoPadrao(false,"Nenhuma requisição foi enviada.");
            }
        }//DistribuirRequisicao

        /**
         * Recebe por padrão os parâmetros 'success' que indica se a operação deu certo ou não.
         * 'mensagem' retorna um informativo ao usuário, referente a requisição.
         * 'dados' quando a requisição tiver algum dado para ser retornado deverá ser incluído nesse parâmetro,
         * mantendo sempre a mesma estrutura.
         */
        public function RetornoPadrao($success, $mensagem, $dados = null){
            // Array de retorno
            $retorno = array('success' => $success,
                            'mensagem' => $mensagem);
            //Se houver dados para retornar, o array de dados é incluído no array de retorno.
            if($dados <> null){
                array_push($retorno, $dados);
            }
            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
        }//RetornoPadrao

        public function ConsultaSimples(){
            // Conexão com o banco de dados
            require '../conexao.php';

            //Faz uma consulta para retornar o id que será utilizado para cadastrar a Despesa
            try{
                $sql =  $db_con->query("SELECT MAX(id) as id FROM fn_despesas");
                $ultimoIDfndespesas = 0;
                foreach ($sql as $value) {
                    $ultimoIDfndespesas = intval($value['id']);
                }
                $IDfndespesas = $ultimoIDfndespesas + 1;

                $this->RetornoPadrao(true,$IDfndespesas);
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                exit;
            }
        }//ConsultaSimples

        /**
         * Roda o script de exportaçaõ do MySQL e cria a estrutura do banco de daos
         */
        public function CriarBancoDeDados(){
            // Conexão com o banco de dados
            require '../conexao.php';

            //Faz uma consulta para retornar o id que será utilizado para cadastrar a Despesa
            try{
                $sql = "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
                START TRANSACTION;
                SET time_zone = '+00:00';
                
                
                /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
                /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
                /*!40101 SET NAMES utf8mb4 */;
                
                --
                -- Banco de dados: `zeta_finances`
                --
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `configuracoes`
                --
                
                DROP TABLE IF EXISTS `configuracoes`;
                CREATE TABLE IF NOT EXISTS `configuracoes` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `tema` varchar(255) DEFAULT NULL,
                  `data_referencia` varchar(255) DEFAULT NULL,
                  `usuarios_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `fk_configuracoes_usuarios_id` (`usuarios_id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_categorias`
                --
                
                DROP TABLE IF EXISTS `fn_categorias`;
                CREATE TABLE IF NOT EXISTS `fn_categorias` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `tipo` varchar(255) DEFAULT NULL,
                  `imagem` varchar(255) DEFAULT NULL,
                  `usuarios_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `fk_fn_categorias_usuarios` (`usuarios_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
                
                --
                -- Extraindo dados da tabela `fn_categorias`
                --
                
                INSERT INTO `fn_categorias` (`id`, `descricao`, `tipo`, `imagem`, `usuarios_id`) VALUES
                (15, 'Lazer', 'Despesa', NULL, 62),
                (16, 'Alimentação', 'Despesa', NULL, 62),
                (17, 'Despesas de Casa', 'Despesa', NULL, 62),
                (18, 'Transporte', 'Despesa', NULL, 62);
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_despesas`
                --
                
                DROP TABLE IF EXISTS `fn_despesas`;
                CREATE TABLE IF NOT EXISTS `fn_despesas` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `fixo` varchar(5) DEFAULT NULL,
                  `vencimento_despesa_fixa` datetime DEFAULT NULL,
                  `valor_despesa_fixa` varchar(255) DEFAULT NULL,
                  `categorias_id` int(11) NOT NULL,
                  `usuarios_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`,`usuarios_id`,`categorias_id`) USING BTREE,
                  KEY `fk_fn_despesas_usuarios_idx` (`usuarios_id`),
                  KEY `fk_fn_despesas_fn_categorias` (`categorias_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_despesas_parcelas`
                --
                
                DROP TABLE IF EXISTS `fn_despesas_parcelas`;
                CREATE TABLE IF NOT EXISTS `fn_despesas_parcelas` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `valorpendente` float DEFAULT NULL,
                  `vencimento` datetime DEFAULT NULL,
                  `valorquitado` float DEFAULT NULL,
                  `quitado` varchar(3) DEFAULT NULL,
                  `quitacao` datetime DEFAULT NULL,
                  `codigo_de_barras` varchar(255) DEFAULT NULL,
                  `observacoes` varchar(999) DEFAULT NULL,
                  `fn_categorias_id` int(11) NOT NULL,
                  `fn_despesas_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`,`fn_categorias_id`,`fn_despesas_id`),
                  KEY `fk_fn_despesas_parcelas_fn_categorias1_idx` (`fn_categorias_id`),
                  KEY `fk_fn_despesas_parcelas_fn_despesas1_idx` (`fn_despesas_id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_receitas`
                --
                
                DROP TABLE IF EXISTS `fn_receitas`;
                CREATE TABLE IF NOT EXISTS `fn_receitas` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `usuarios_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`,`usuarios_id`),
                  KEY `fk_fn_receitas_usuarios1_idx` (`usuarios_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_receitas_parcelas`
                --
                
                DROP TABLE IF EXISTS `fn_receitas_parcelas`;
                CREATE TABLE IF NOT EXISTS `fn_receitas_parcelas` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `valorpendente` float DEFAULT NULL,
                  `vencimento` datetime DEFAULT NULL,
                  `valorquitado` float DEFAULT NULL,
                  `quitado` varchar(3) DEFAULT NULL,
                  `quitacao` datetime DEFAULT NULL,
                  `fn_categorias_id` int(11) NOT NULL,
                  `fn_receitas_id` int(11) NOT NULL,
                  `fixo` varchar(3) DEFAULT NULL,
                  PRIMARY KEY (`id`,`fn_categorias_id`,`fn_receitas_id`),
                  KEY `fk_fn_receitas_parcelas_fn_categorias1_idx` (`fn_categorias_id`),
                  KEY `fk_fn_receitas_parcelas_fn_receitas1_idx` (`fn_receitas_id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `usuarios`
                --
                
                DROP TABLE IF EXISTS `usuarios`;
                CREATE TABLE IF NOT EXISTS `usuarios` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `cpf` varchar(45) NOT NULL,
                  `rg` varchar(45) DEFAULT NULL,
                  `nome` varchar(45) NOT NULL,
                  `sobrenome` varchar(45) DEFAULT NULL,
                  `email` varchar(45) NOT NULL,
                  `telefone` varchar(45) DEFAULT NULL,
                  `celular` varchar(45) DEFAULT NULL,
                  `cep` varchar(45) DEFAULT NULL,
                  `logradouro` varchar(45) DEFAULT NULL,
                  `complemento` varchar(45) DEFAULT NULL,
                  `bairro` varchar(45) DEFAULT NULL,
                  `cidade` varchar(45) DEFAULT NULL,
                  `estado` varchar(45) DEFAULT NULL,
                  `imagem` varchar(45) DEFAULT NULL,
                  `cadastro` date DEFAULT NULL,
                  `senha` varchar(999) DEFAULT NULL,
                  `dica` varchar(999) DEFAULT NULL,
                  `user_session_id` varchar(999) DEFAULT NULL,
                  `user_permissions` varchar(999) DEFAULT NULL,
                  `user` varchar(999) DEFAULT NULL,
                  `situacao` varchar(40) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
                
                --
                -- Extraindo dados da tabela `usuarios`
                --
                
                INSERT INTO `usuarios` (`id`, `cpf`, `rg`, `nome`, `sobrenome`, `email`, `telefone`, `celular`, `cep`, `logradouro`, `complemento`, `bairro`, `cidade`, `estado`, `imagem`, `cadastro`, `senha`, `dica`, `user_session_id`, `user_permissions`, `user`, `situacao`) VALUES
                (62, '75463458120', '58364152', 'Bruno Mateus', 'Silva Souza', 'bruno_mss@outlook.com', '35862047', '62994626462', '74583050', 'Rua Trajano de Sá Guimaraes', 'Qd 09 Lt 05', 'Vila Maria Dilce', 'Goiânia', 'GO', 'foto3x4png_426531666.png', NULL, '$2a$08$icg7xTO4I72ef15BJz9EgeY5WKjfFtCLXjLob1t/U6rMYXUWrH2x6', 'hid07vkouoir68rh4fkhl64pt5', '7fj3b71mo509tcrdo2gnlipi3s', 'a:2:{i:0;s:19:\"AcessarAreaRestrita\";i:1;s:16:\"AcessarSiteAdmin\";}', 'bruno_mss@outlook.com', 'Ativo'),
                (63, '004.429.532-60', '4399743', 'Renan', 'Além Silva', 'renanalem@alemtecnologia.com.br', '(35) 8680-27', '(62) 9 8513-4662', '87984-65', '5646546546', '654654654', '465465465', '4564654', '54654654', '5jpg_963156278.jpg', '2020-07-19', '$2a$08$icg7xTO4I72ef15BJz9EgeY5WKjfFtCLXjLob1t/U6rMYXUWrH2x6', 'Senha 123', '111eda0e3dc780ff0ef3c31f51574d73', 'a:2:{i:0;s:19:\"AcessarAreaRestrita\";i:1;s:16:\"AcessarSiteAdmin\";}', 'renanalem@alemtecnologia.com.br', 'Ativo'),
                (64, '75463458121', '1254635', 'Carlos daniel', 'souza pires', 'carlos@gmail.com', '62 3586-2047', '62 9 9462-6462', '74583050', 'Rua Trajano de Sá Guimarães', 'Qd 09 Lt 05', 'Jardim Clarissa', 'Goiânia', 'GO', 'foto3x4png_1156961214.png', '2021-03-16', '$2a$08$sthdPYQtn63aaqBii7nX2u5J08NSslg2yX5eFIyo.Vs7oStd6Cb7q', 'teste', NULL, 'AcessarAreaRestrita', 'carlos@gmail.com', 'Ativo');
                
                --
                -- Restrições para despejos de tabelas
                --
                
                --
                -- Limitadores para a tabela `fn_despesas`
                --
                ALTER TABLE `fn_despesas`
                  ADD CONSTRAINT `fk_fn_despesas_fn_categorias` FOREIGN KEY (`categorias_id`) REFERENCES `fn_categorias` (`id`);
                COMMIT;
                
                /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
                /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
                /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

                $conexao =  $db_con->query($sql);

                $this->RetornoPadrao(true,"Estrutura do banco de dados criado com sucesso!");
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao criar banco de dados! - ".$e->getMessage(), "\n");
                exit;
            }
        }//CriarBancoDeDados

        /**
         * Faz a inclusão de novas despesas no banco de dados
         * Esse método recebe via POST um array multidimensional, dentro do array principal tem o userID - que é o código do usuario logado
         * E também a descrição da despesa, dentro desse array principal, tem um array com a lista das despesas. 
         * Primeiramente é feito uma consulta na tabela fn_despesas para pegar o código da última despesa cadastrada
         * Em seguida executo um loop para inserir as parcelas do array de parcelas no banco de dados.
         */
        public function IncluirDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados do arrayCabecalhoDespesa
            $userID = $_POST['arrayDespesa'][0]['userID'];
            $descricao = $_POST['arrayDespesa'][0]['descricao'];
            $categoria = $_POST['arrayDespesa'][0]['categoria'];

            //Faz uma consulta para retornar o id que será utilizado para cadastrar a Despesa
            try{
                $sql =  $db_con->query("SELECT MAX(id) as id FROM fn_despesas");
                $ultimoIDfndespesas = 0;
                foreach ($sql as $value) {
                    $ultimoIDfndespesas = intval($value['id']);
                }
                $IDfndespesas = $ultimoIDfndespesas + 1;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                exit;
            }
        
            //Salva os dados da Despesa no banco de dados
            try{
                $sql =  $db_con->query("INSERT INTO `fn_despesas` (`id`,`descricao`,`categorias_id`,`usuarios_id`) VALUES ('{$IDfndespesas}','{$descricao}','{$categoria}','{$userID}')");      
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                exit;
            }

            //Recebe os dados do arrayParcelasDespesa, em seguida percorre todo o array através do foreach e insere os dados das parcelas no banco de dados
            $arrayParcelasDespesa = [];
            $arrayParcelasDespesa = $_POST['arrayDespesa'][1];

            /**Bruno se tiver tudo funcionando, apagar essse código comentado abaixo
             * ele errá utilizado para incluir as parcelas das despesas, porém achei melhor separar as funções
             * para dar mais dinamicidade a API 
             */
            // $parcela = "";
            // $vencimento = "";
            // $valor = "";
            // $qteParcelas = sizeof($arrayParcelasDespesa);
            // $categoriaParcela = 0;
 
            // foreach ($arrayParcelasDespesa as $value) {
            //     $parcela = $value['Parcela'];//Número da parcela informado na descrição da parcela
            //     $vencimento = $value['Vencimento'];
            //     $vencimento = implode("-",array_reverse(explode("/",$vencimento)));//Entender e documentar essa função aqui
            //     $valor = strval($value['Valor']);
            //     $descricaoParcela = $parcela;
            //     $categoriaParcela = $value['Categoria'];
            //     $codigoDeBarras = $value['CodigoDeBarras'];
            //     $observacoes = $value['Observacoes'];
                
            //     try{
            //         $sql =  $db_con->query("INSERT INTO `fn_despesas_parcelas`
            //         (`descricao`,`valorpendente`,`vencimento`,`quitado`,`codigo_de_barras`,`observacoes`,`fn_categorias_id`,`fn_despesas_id`) 
            //         VALUES 
            //         ('{$descricaoParcela}','{$valor}','{$vencimento}','NÃO','{$codigoDeBarras}','{$observacoes}','{$categoriaParcela}','{$IDfndespesas}')");           
            //     }
            //     catch (Exception $e){
            //         $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
            //         exit;
            //     }
            // }
            $despesasForamCadastradas  = $this->IncluirParcelaDespesa($arrayParcelasDespesa, $IDfndespesas);

            if ($despesasForamCadastradas == true){
                $this->RetornoPadrao(true,"Despesa cadastrada com sucesso!");
                exit;
            }else{
                $this->RetornoPadrao(false,"Erro ao cadastrar as parcelas da despesa!");
                exit;
                //Bruno fazer a chamada do método para excluir Despesa, uma vez que a despesa foi cadastrada porem deu erro ao cadastrar as parcelas
            }
            
        }//IncluirDespesa

        /**
         * Faz a inclusão das parcelas de despesa no banco de dados
         * Esse método recebe via parâmetro ou via POST um array contendo os dados das parcelas a serem cadastradas
         * E também a o ID da despesa que já deverá ter sido previamente cadastrada
         * Antes todo o processo era feito de forma unica no método 'IncluirDespesa', porém surgiu a necessidade de incrementar parcelas em despesas
         * Esse método retorna boleano, indicando se a operação de cadastro foi realizada com sucesso ou não
         */
        public function IncluirParcelaDespesa($arrayDadosParcela = [], $IDfndespesas){
            // Conexão com o banco de dados
            require '../conexao.php';

            $modoDeInclusao =  "ViaParametro";

            /*Verifica se os dados da despesa foram recebidos via parâmetro (função utilizada ao cadastrar uma nova despesa)
             *senão ele verifica se existe algo no POST, utilizado na tela de editar despesa quando o usuário inclui uma nova despesa
             */
            if(empty($arrayDadosParcela)){
                $arrayDadosParcela = [];
                if (isset($_POST['arrayParcelaDespesa'])){
                    $arrayDadosParcela = $_POST['arrayParcelaDespesa'];
                    $modoDeInclusao =  "ViaPOST";
                }
                if (isset($_POST['idDespesa'])){
                    $IDfndespesas = $_POST['idDespesa'];
                }
            }

            $parcela = "";
            $vencimento = "";
            $valor = "";
            $qteParcelas = sizeof($arrayDadosParcela);
            $categoriaParcela = 0;
 
            foreach ($arrayDadosParcela as $value) {
                $parcela = $value['Parcela'];//Número da parcela informado na descrição da parcela
                $vencimento = $value['Vencimento'];
                $vencimento = implode("-",array_reverse(explode("/",$vencimento)));//Entender e documentar essa função aqui
                $valor = strval($value['Valor']);
                $descricaoParcela = $parcela;
                $categoriaParcela = $value['Categoria'];
                $codigoDeBarras = $value['CodigoDeBarras'];
                $observacoes = $value['Observacoes'];
                
                try{
                    $sql =  $db_con->query("INSERT INTO `fn_despesas_parcelas`
                    (`descricao`,`valorpendente`,`vencimento`,`quitado`,`codigo_de_barras`,`observacoes`,`fn_categorias_id`,`fn_despesas_id`) 
                    VALUES 
                    ('{$descricaoParcela}','{$valor}','{$vencimento}','NÃO','{$codigoDeBarras}','{$observacoes}','{$categoriaParcela}','{$IDfndespesas}')");           
                }
                catch (Exception $e){
                    if($modoDeInclusao ==  "ViaPOST"){
                        $this->RetornoPadrao(false,"Erro ao cadastrar parcela! - ".$e->getMessage(), "\n");
                        exit;
                    }else{//ViaParametro
                        return false;
                        exit;
                    }
                }
            }
            if($modoDeInclusao ==  "ViaPOST"){
                $this->RetornoPadrao(true,"Parcela cadastrada com sucesso!");
                exit;
            }else{//ViaParametro
                return true;
                exit;
            }
            
        }//IncluirParcelaDespesa

        public function AlterarParcelaDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';

            $arrayDadosParcela = $_POST['arrayParcelaDespesa'];
            $IDfndespesas = $_POST['idDespesa'];
            $IDParcela = $_POST['idParcela'];

            $parcela = "";
            $vencimento = "";
            $valor = "";
            $qteParcelas = sizeof($arrayDadosParcela);
            $categoriaParcela = 0;
 
            foreach ($arrayDadosParcela as $value) {
                $parcela = $value['Parcela'];//Número da parcela informado na descrição da parcela
                $vencimento = $value['Vencimento'];
                $vencimento = implode("-",array_reverse(explode("/",$vencimento)));//Entender e documentar essa função aqui
                $valor = strval($value['Valor']);
                $descricaoParcela = $parcela;
                $categoriaParcela = $value['Categoria'];
                $codigoDeBarras = $value['CodigoDeBarras'];
                $observacoes = $value['Observacoes'];
                
                try{
                    $sql =  $db_con->query("UPDATE fn_despesas_parcelas SET 
                        descricao = '{$descricaoParcela}',
                        valorpendente = '{$valor}',
                        vencimento = '{$vencimento}',
                        codigo_de_barras = '{$codigoDeBarras}',
                        observacoes = '{$observacoes}',
                        fn_categorias_id = '{$categoriaParcela}'
                    WHERE fn_despesas_id = {$IDfndespesas} 
                        AND id = {$IDParcela}");           
                }
                catch (Exception $e){
                    $this->RetornoPadrao(false,"Erro ao alterar parcela! - ".$e->getMessage(), "\n");
                    exit;
                }
            }
            $this->RetornoPadrao(true,"Parcela alterada com sucesso!");
            exit;
        }//AlterarParcelaDespesa

        public function ExcluirParcelaDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';

            $userID = $_POST['userID'];
            $IDfndespesas = $_POST['idDespesa'];
            $IDParcela = $_POST['idParcela'];

            try{
                $sql =  $db_con->query("DELETE FROM fn_despesas_parcelas WHERE fn_despesas_id='{$IDfndespesas}' AND id = '{$IDParcela}'");           
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao excluir parcela! - ".$e->getMessage(), "\n");
                exit;
            }

            $this->RetornoPadrao(true,"Parcela excluída com sucesso!");
            exit;
        }//ExcluirParcelaDespesa

        /**
         * Faz a inclusão de despesas fixas
         */
        public function IncluirDespesaFixa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados do arrayCabecalhoDespesa
            $userID = $_POST['userID'];
            $descricao = $_POST['descricao'];
            $vencimento = $_POST['vencimento'];
            $valor = $_POST['valor'];
            $categoria = $_POST['categoria'];
        
            //Salva a despesa no banco de dados
            try{
                $sql =  $db_con->query("INSERT INTO `fn_despesas` (`descricao`,`fixo`,`vencimento_despesa_fixa`,`valor_despesa_fixa`,`categorias_id`,`usuarios_id`) VALUES ('{$descricao}','SIM','{$vencimento}','{$valor}','{$categoria}','{$userID}')");      
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                exit;
            }
            
            $this->RetornoPadrao(true,"Despesa fixa cadastrada com sucesso!");
            exit;

        }//IncluirDespesaFixa

        /**
         * Alterar Despesa
         * Altera apenas o cabeçalho da despesa
         * Esse método recebe via POST os parâmetros userID, idDespesa, descricão
         */
        public function AlterarDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $idDespesa = $_POST['idDespesa'];
            $descricao = $_POST['descricao'];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "UPDATE fn_despesas SET
                    descricao = '{$descricao}',
                    fixo = NULL,
                    vencimento_despesa_fixa = NULL,
                    valor_despesa_fixa = NULL
                WHERE id = {$idDespesa}
                    AND usuarios_id = {$userID}";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao alterar despesa - ".$e->getMessage(), "\n");
                    exit;
                }

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesa alterada com sucesso!");
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao alterar despesa - ".$e->getMessage(), "\n");
                exit;
            }
        }//AlterarDespesa

        /**
         * Alterar Despesa FIxa
         * Altera apenas o cabeçalho da despesa
         * Esse método recebe via POST os parâmetros userID, idDespesa, descricão
         */
        public function AlterarDespesaFixa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $idDespesa = $_POST['idDespesa'];
            $descricao = $_POST['descricao'];
            $vencimento = $_POST['vencimento'];
            $valor = $_POST['valor'];
            $categoria = $_POST['categoria'];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "UPDATE fn_despesas SET
                    descricao = '{$descricao}',
                    vencimento_despesa_fixa = '{$vencimento}',
                    valor_despesa_fixa = '{$valor}',
                    categorias_id = {$categoria},
                    fixo = 'SIM'
                WHERE id = {$idDespesa}
                    AND usuarios_id = {$userID}"; 

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao alterar despesa fixa - ".$e->getMessage(), "\n");
                    exit;
                }

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesa fixa alterada com sucesso!");
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao alterar despesa fixa - ".$e->getMessage(), "\n");
                exit;
            }
        }//AlterarDespesaFixa

        /**
         * Faz a inclusão de novas categorias
         * Esse método recebe via POST os parâmetros para o cadastro e retorna o id da categoria cadastrada.
         */
        public function IncluirCategoria(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $descricao = $_POST['descricao'];
            $tipo = $_POST['tipo'];
            //Padroniza o texto da descrição para capitalize
            ucfirst(strtolower($descricao));

            $idRetorno = 0;

            //Salva os dados da Categoria no banco de dados
            try{
                $sql =  $db_con->query("INSERT INTO `fn_categorias` (`descricao`,`tipo`,`usuarios_id`) VALUES ('{$descricao}','{$tipo}',{$userID})");      
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar categoria! - ".$e->getMessage(), "\n");
                exit;
            }

            //Faz uma consulta para retornar o id da categoria cadastrada
            try{
                $sql =  $db_con->query("SELECT MAX(id) as id FROM fn_categorias WHERE usuarios_id = {$userID}");
                $ultimoIDfncategorias = 0;
                foreach ($sql as $value) {
                    $ultimoIDfncategorias = intval($value['id']);
                }
                $idRetorno = $ultimoIDfncategorias;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar categoria! - ".$e->getMessage(), "\n");
                exit;
            }

            $dados = array(
                "id" => $idRetorno,
            );
            
            
            $this->RetornoPadrao(true,"Categoria cadastrada com sucesso!",$dados);
            exit;

        }//IncluirCategoria

        /**
         * Lista todas as despesas por mês 
         * Esse método recebe via POST os parâmetros mes, ano e userID
         */
        public function ListarDespesasMensal(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $dataReferencia = $_POST['dataReferencia'];
            $dataReferencia = $dataReferencia.'-01';

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "SELECT fn_despesas.id,
                    fn_despesas.descricao,
                    SUM(COALESCE(fn_despesas_parcelas.valorpendente,0)) AS valorpendente,
                    SUM(COALESCE(fn_despesas_parcelas.valorquitado,0)) AS valorquitado,
                    fn_despesas_parcelas.quitado,
                    fn_despesas_parcelas.vencimento,
                    fn_despesas_parcelas.quitacao,
                    COUNT(fn_despesas_parcelas.ID) AS quantidadeparcelas
                FROM fn_despesas_parcelas
                    INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id = fn_despesas.id
                WHERE usuarios_id = {$userID}
                    AND DATE_FORMAT(vencimento, '%Y-%m') = DATE_FORMAT('{$dataReferencia}', '%Y-%m')
                GROUP BY fn_despesas.id, fn_despesas.descricao, Fn_despesas_parcelas.quitado, fn_despesas_parcelas.vencimento, fn_despesas_parcelas.quitacao
                ORDER BY fn_despesas.descricao ASC";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao consultar Despesas - ".$e->getMessage(), "\n");
                    exit;
                }

                //O método fetchAll transforma o resultado da consulta em um array
                //O parâmetro PDO::FETCH_ASSOC inclui os indices(nomes das colunas) no array em vez do número
                $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesas listadas com sucesso!",$result);
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao consultar Despesas - ".$e->getMessage(), "\n");
                exit;
            }
        }//ListarDespesasMensal

        /**
         * Quita Despesa
         * Esse método recebe via POST os parâmetros userID, idDespesa, qtdeParcelas, vencimento, quitacao, valorQuitado
         */
        public function QuitarDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $idDespesa = $_POST['idDespesa'];
            $qtdeParcelas = $_POST['qtdeParcelas'];
            $vencimento = $_POST['vencimento'];
            $quitacao = $_POST['quitacao'];
            $valorQuitado = $_POST['valorQuitado'];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                if(intval($qtdeParcelas) > 1){
                    $sql = "UPDATE fn_despesas_parcelas SET
                        valorquitado = valorpendente,
                        quitado = 'SIM',
                        quitacao = '{$quitacao}'
                    WHERE fn_despesas_id = {$idDespesa}
                        AND vencimento = '{$vencimento}'";
                }else{
                    $sql = "UPDATE fn_despesas_parcelas SET
                        valorquitado = '{$valorQuitado}',
                        quitado = 'SIM',
                        quitacao = '{$quitacao}'
                    WHERE fn_despesas_id = {$idDespesa}
                        AND vencimento = '{$vencimento}'";
                }

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao quitar despesa - ".$e->getMessage(), "\n");
                    exit;
                }

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesa quitada com sucesso!");
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao quitar despesa - ".$e->getMessage(), "\n");
                exit;
            }
        }//QuitarDespesa

        /**
         * Estorna Despesa
         * Esse método recebe via POST os parâmetros userID, idDespesa, qtdeParcelas, vencimento
         */
        public function EstornarDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $idDespesa = $_POST['idDespesa'];
            $qtdeParcelas = $_POST['qtdeParcelas'];
            $vencimento = $_POST['vencimento'];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "UPDATE fn_despesas_parcelas SET
                    valorquitado = NULL,
                    quitado = 'NÃO',
                    quitacao = NULL
                WHERE fn_despesas_id = {$idDespesa}
                    AND vencimento = '{$vencimento}'";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao estornar despesa - ".$e->getMessage(), "\n");
                    exit;
                }

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesa estornada com sucesso!");
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao estornar despesa - ".$e->getMessage(), "\n");
                exit;
            }
        }//EstornarDespesa

        /**
         * Retorna os dados de uma despesa específica por código (Informações do cabeçalho da despesa e as parcelas que esão pendentes)
         * Esse método recebe via POST os parâmetros userID, idDespesa, qtdeParcelas, vencimento
         */
        public function ListarDadosDespesaPendentePorCodigo(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $despesaID = $_POST['despesaID'];
            $dataReferencia = $_POST['dataReferencia'];
            $arrayRetorno = [];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "SELECT * FROM fn_despesas WHERE id = {$despesaID} AND usuarios_id = {$userID}";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao consultar Despesas - ".$e->getMessage(), "\n");
                    exit;
                }
                //O método fetchAll transforma o resultado da consulta em um array
                //O parâmetro PDO::FETCH_ASSOC inclui os indices(nomes das colunas) no array em vez do número
                $arrayCabecalhoDespesa = $consulta->fetchAll(PDO::FETCH_ASSOC);
                array_push($arrayRetorno, $arrayCabecalhoDespesa);

                //Consulta as parcelas -------------------------------------------------------------------------

                $sql = "SELECT * FROM fn_despesas_parcelas 
                WHERE fn_despesas_id = {$despesaID} 
                    AND quitado = 'NÃO' 
                    AND vencimento = '{$dataReferencia}'";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao consultar Despesas - ".$e->getMessage(), "\n");
                    exit;
                }
                //O método fetchAll transforma o resultado da consulta em um array
                //O parâmetro PDO::FETCH_ASSOC inclui os indices(nomes das colunas) no array em vez do número
                $arrayParcelasDespesa = $consulta->fetchAll(PDO::FETCH_ASSOC);
                array_push($arrayRetorno, $arrayParcelasDespesa);

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Despesas listadas com sucesso!",$arrayRetorno);
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao consultar Despesas - ".$e->getMessage(), "\n");
                exit;
            }
        }//ListarDadosDespesaPendentePorCodigo

        /**
         * Atualizar Data de Referência
         * Atualiza a data do mes que é utilizada como referencia para consultar as receitas e despesas
         * Esse método recebe via POST os parâmetros userID, dataReferencia
         */
        public function AtualizarDataReferencia(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];
            $dataReferencia = $_POST['dataReferencia'];

            try{
                //Verifico se já existe um registro de configuração para o usuário
                $sql = "SELECT COUNT(*) FROM configuracoes WHERE usuarios_id = {$userID}";
                $consulta =  $db_con->query($sql);
                $count = $consulta->fetchColumn();

                if($count == 0){
                    $sql = "INSERT INTO configuracoes (data_referencia, usuarios_id)
                    VALUES ('{$dataReferencia}', {$userID} )";
                }else{
                    $sql = "UPDATE configuracoes SET
                    data_referencia = '{$dataReferencia}'
                    WHERE
                    usuarios_id = {$userID}";
                }

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao atualizar data de referência - ".$e->getMessage(), "\n");
                    exit;
                }

                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Data de referência atualizada com sucesso!");
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao atualizar data de referência - ".$e->getMessage(), "\n");
                exit;
            }
        }//AtualizarDataReferencia

        /**
         * Lista todas as despesas por mês 
         * Esse método recebe via POST os parâmetros mes, ano e userID
         */
        public function BuscarDataReferencia(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados via POST
            $userID = $_POST['userID'];

            //Faz uma consulta para retornar um array com todas as despesas listadas
            try{
                $sql = "SELECT data_referencia
                FROM configuracoes
                WHERE usuarios_id = {$userID}";

                $consulta =  $db_con->query($sql);

                if(!$consulta){
                    $this->RetornoPadrao(false,"Erro ao consultar data de referência - ".$e->getMessage(), "\n");
                    exit;
                }

                //O método fetchAll transforma o resultado da consulta em um array
                //O parâmetro PDO::FETCH_ASSOC inclui os indices(nomes das colunas) no array em vez do número
                $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
                //Faz o retorno dos dados
                $this->RetornoPadrao(true,"Data de referência listada com sucesso!",$result);
                exit;
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao consultar data de referência - ".$e->getMessage(), "\n");
                exit;
            }
        }//BuscarDataReferencia

        public function tempo_corrido($time) {

            $now = strtotime(date('m/d/Y H:i:s'));
            $time = strtotime($time);
            $diff = $now - $time;
    
            $seconds = $diff;
            $minutes = round($diff / 60);
            $hours = round($diff / 3600);
            $days = round($diff / 86400);
            $weeks = round($diff / 604800);
            $months = round($diff / 2419200);
            $years = round($diff / 29030400);
    
            if ($seconds <= 60) return $seconds==1 ?'1 seg atrás':$seconds.' seg atrás';
            //if ($seconds <= 60) return"30 seg atrás";
            else if ($minutes <= 60) return $minutes==1 ?'1 min atrás':$minutes.' min atrás';
            else if ($hours <= 24) return $hours==1 ?'1 hr atrás':$hours.' hrs atrás';
            else if ($days <= 7) return $days==1 ?'1 dia atrás':$days.' dias atrás';
            else if ($weeks <= 4) return $weeks==1 ?'1 semana atrás':$weeks.' semanas atrás';
            else if ($months <= 12) return $months == 1 ?'1 mês atrás':$months.' meses atrás';
            else return $years == 1 ? 'um ano atrás':$years.' anos atrás';
        }//tempo_corrido
    }//Class FinancesAPI
    
//echo"chegou ao final sem erros";

//*********************************************************************************************************************

 ?>