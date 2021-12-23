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
            if($requisicao == ""){
                $this->RetornoPadrao(false,"Nenhuma requisição foi enviada.");
            }
            elseif($requisicao == "consultaSimples"){
                $this->ConsultaSimples();
            }
            elseif($requisicao == "criarBancoDeDados"){
                $this->CriarBancoDeDados();
            }
            elseif($requisicao == "incluirDespesa"){
                $this->IncluirDespesa();
            }
            elseif($requisicao == "incluirDespesaFixa"){
                $this->IncluirDespesaFixa();
            }
            elseif($requisicao == "incluirCategoria"){
                $this->IncluirCategoria();
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
                $sql = "-- phpMyAdmin SQL Dump
                -- version 5.0.2
                -- https://www.phpmyadmin.net/
                --
                -- Host: 127.0.0.1:3306
                -- Tempo de geração: 21-Dez-2021 às 20:28
                -- Versão do servidor: 5.7.31
                -- versão do PHP: 7.3.21
                
                SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
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
                ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
                
                --
                -- Extraindo dados da tabela `fn_categorias`
                --
                
                INSERT INTO `fn_categorias` (`id`, `descricao`, `tipo`, `imagem`, `usuarios_id`) VALUES
                (2, 'Outras', 'Despesa', '', 62);
                
                -- --------------------------------------------------------
                
                --
                -- Estrutura da tabela `fn_despesas`
                --
                
                DROP TABLE IF EXISTS `fn_despesas`;
                CREATE TABLE IF NOT EXISTS `fn_despesas` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `descricao` varchar(255) DEFAULT NULL,
                  `fixo` varchar(5) DEFAULT NULL,
                  `valor_despesa_fixa` varchar(255) DEFAULT NULL,
                  `categorias_id` int(11) NOT NULL,
                  `usuarios_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`,`usuarios_id`,`categorias_id`) USING BTREE,
                  KEY `fk_fn_despesas_usuarios_idx` (`usuarios_id`),
                  KEY `fk_fn_despesas_fn_categorias` (`categorias_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;
                
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
                ) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
                
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
                (62, '75463458120', '58364152', 'Bruno Mateus', 'Silva Souza', 'bruno_mss@outlook.com', '35862047', '62994626462', '74583050', 'Rua Trajano de Sá Guimaraes', 'Qd 09 Lt 05', 'Vila Maria Dilce', 'Goiânia', 'GO', 'foto3x4png_426531666.png', NULL, '$2a$08$icg7xTO4I72ef15BJz9EgeY5WKjfFtCLXjLob1t/U6rMYXUWrH2x6', 'hid07vkouoir68rh4fkhl64pt5', '9e10nsc88tm4juljfqb57qmiof', 'a:2:{i:0;s:19:\"AcessarAreaRestrita\";i:1;s:16:\"AcessarSiteAdmin\";}', 'bruno_mss@outlook.com', 'Ativo'),
                (63, '00442953260', '4399743', 'Renan', 'Além Silva', 'renanalem@alemtecnologia.com.br', '35868027', '62985134662', '8798465', '5646546546', '654654654', '465465465', '4564654', '54654654', '5jpg_963156278.jpg', '2020-07-19', '$2a$08$icg7xTO4I72ef15BJz9EgeY5WKjfFtCLXjLob1t/U6rMYXUWrH2x6', 'Senha 123', '111eda0e3dc780ff0ef3c31f51574d73', 'a:2:{i:0;s:13:\"user-register\";i:1;s:18:\"gerenciar-noticias\";}', 'renanalem@alemtecnologia.com.br', ''),
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
            
            $parcela = "";
            $vencimento = "";
            $valor = "";
            $qteParcelas = sizeof($arrayParcelasDespesa);
            $categoriaParcela = 0;
 
            foreach ($arrayParcelasDespesa as $value) {
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
                    $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                    exit;
                }
            }
            
            $this->RetornoPadrao(true,"Despesa cadastrada com sucesso!");
            exit;

        }//IncluirDespesa

        /**
         * Faz a inclusão de despesas fixas
         */
        public function IncluirDespesaFixa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados do arrayCabecalhoDespesa
            $userID = $_POST['userID'];
            $descricao = $_POST['descricao'];
            $valor = $_POST['valor'];
            $categoria = $_POST['categoria'];
        
            //Salva a despesa no banco de dados
            try{
                $sql =  $db_con->query("INSERT INTO `fn_despesas` (`descricao`,`fixo`,`valor_despesa_fixa`,`categorias_id`,`usuarios_id`) VALUES ('{$descricao}','SIM','{$valor}','{$categoria}','{$userID}')");      
            }
            catch (Exception $e){
                $this->RetornoPadrao(false,"Erro ao cadastrar despesa! - ".$e->getMessage(), "\n");
                exit;
            }
            
            $this->RetornoPadrao(true,"Despesa fixa cadastrada com sucesso!");
            exit;

        }//IncluirDespesaFixa

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