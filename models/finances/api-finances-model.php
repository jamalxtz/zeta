<?php 
    /* Prepara o documento para comunicação com o JSON, as duas linhas a seguir são obrigatórias 
	  para que o PHP saiba que irá se comunicar com o JSON, elas sempre devem estar no ínicio da página */
	// header("Cache-Control: no-cache, no-store, must-revalidate"); // Limpa o cache
	// header("Access-Control-Allow-Origin: *");
	// header("Content-Type: application/json; charset=utf-8"); 
	// // Limpa o cache
	// clearstatcache(); 
    

//******************* DECLARAÇÃO DE VARIÁVEIS *************************************************************************
    /*Recebe a requisição via POST e redireciona para o método responsável por tratar essa requisição
    *Para testar basta procar o _POST por _GET e utilizar o seguinte padrão de URL:
    *http://localhost:8090/zeta/zeta/models/finances/api-finances-model.php?requisicao=consultaSimples&outroParametro=22
    */
    if (isset($_GET['requisicao'])){
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
        public $dataAtual = date('Y/m/d H:i:s');
        
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

        public function IncluirDespesa(){
            // Conexão com o banco de dados
            require '../conexao.php';
            //Recebe os dados do arrayCabecalhoDespesa
            $userID = $_POST['arrayDespesa'][0]['userID'];
            $descricao = $_POST['arrayDespesa'][0]['descricao'];

            //Faz uma consulta para retornar o id que será utilizado para cadastrar a Despesa
            try{
                $sql =  $this->$db_con->query("SELECT MAX(id) as id FROM fn_despesas");
                $ultimoIDfndespesas = 0;
                foreach ($sql as $value) {
                    $ultimoIDfndespesas = intval($value['id']);
                }
                $IDfndespesas = $ultimoIDfndespesas + 1;
            }
            catch (Exception $e){
                $retorno['success'] = false;
                $retorno['mensagem'] = "Erro ao cadastrar despesa! - ".$e;
                echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
                exit;
            }
        
            //Salva os dados da Despesa no banco de dados
            try{
                $sql =  $this->$db_con->query("INSERT INTO `fn_despesas` (`id`,`descricao`,`usuarios_id`) VALUES ('{$IDfndespesas}','{$descricao}','{$userID}')");
                $retorno['success'] = true;
                //$retorno['mensagem'] = "Despesa cadastrada com sucesso!";
                //echo json_encode($retorno, JSON_UNESCAPED_UNICODE);              
            }
            catch (Exception $e){
                $retorno['success'] = false;
                $retorno['mensagem'] = "Erro ao cadastrar despesa! - ".$e;
                echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
                exit;
            }

            //Recebe os dados do arrayParcelasDespesa, em seguida percorre todo o array através do foreach e insere os dados das parcelas no banco de dados
            $arrayParcelasDespesa = [];
            $arrayParcelasDespesa = $_POST['arrayDespesa'][1];
            
            $parcela = "";
            $data = "";
            $valor = "";
            $qteParcelas = sizeof($arrayParcelasDespesa);
            foreach ($arrayParcelasDespesa as $value) {
                $parcela = $value['Parcela'];
                $data = $value['Data'];
                $data = implode("-",array_reverse(explode("/",$data)));;
                $valor = strval($value['Valor']);
                $descricaoParcela = $parcela."/".$qteParcelas;

                try{
                    $sql =  $db_con->query("INSERT INTO `fn_despesas_parcelas`
                    (`descricao`,`valorpendente`,`vencimento`, `quitado`, `fn_categorias_id`, `fn_despesas_id`, `fixo`) 
                    VALUES 
                    ('{$descricaoParcela}','{$valor}','{$data}','NÃO', '1', '{$IDfndespesas}','NÃO')");

                    $retorno['success'] = true;             
                }
                catch (Exception $e){
                    $retorno['success'] = false;
                    $retorno['mensagem'] = "Erro ao cadastrar despesa! - ".$e;
                    echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            
            //Verifica se até aqui todos as operações foram executadas com sucesso e então retorna a mensagem indicando que a despesa foi cadastrada com sucesso.
            if($retorno['success'] == true){
                $retorno['mensagem'] = "Despesa cadastrada com sucesso!";
                echo json_encode($retorno, JSON_UNESCAPED_UNICODE); 
            }
        }//IncluirDespesa

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