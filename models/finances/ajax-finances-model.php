<?php 

    // Conexão com o banco de dados
    require '../conexao.php';

//******************* DECLARAÇÃO DE VARIAVEIS *************************************************************************

    if (isset($_POST['varFuncao'])){
        //Variaveis recebidas por POST
        $varFuncao = $_POST['varFuncao'];
    }else{
        $varFuncao = "";
    }

    //Pega a data atual
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date('Y/m/d H:i:s');


    // Array de retorno
    $retorno = array('success' => false,
                     'mensagem' => "erro",
                     'id' => 0,
                     //retorno receitas
                     'rc_id' => 0,
                     'rc_descricao' => 0,
                     'rc_valorpendente' => 0,
                     'rc_vencimento' => 0,
                     'rc_valorquitado' => 0,
                     'rc_fixo' => 0,
                     'rc_quitado' => 0,
                     'rc_quitacao' => 0,
                     'rc_usuarios_id' => 0,
                     //retorno despesas
                     'dp_id' => 0,
                     'dp_descricao' => 0,
                     'dp_valorpendente' => 0,
                     'dp_vencimento' => 0,
                     'dp_valorquitado' => 0,
                     'dp_fixo' => 0,
                     'dp_quitado' => 0,
                     'dp_quitacao' => 0,
                     'dp_usuarios_id' => 0);


//******************* GRÁFICO RECEITAS ********************************************************************************
    /* 
    Retorna um JSON para alimentar os dados do gráfico de receitas
    */

    if($varFuncao == "graficoReceitas"){

        //$usuario = $_POST['usuario'];
        //$data = $_POST['data'];
        // Falta passar os parametros da data e do usuario

        $usuario = 62;
        $data = "2021/04/01";
       
        try{
            $query =  $db_con->query("SELECT fn_receitas.descricao as descricao, (CASE WHEN quitado = 'NÃO' THEN valorpendente ELSE valorquitado END) AS valorpendente 
                                    FROM fn_receitas_parcelas
                                    INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id
                                    WHERE usuarios_id = ".$usuario."
                                    AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')");

            $data = [];

            foreach ($query as $value) {
                $data[$value['descricao']] = $value["valorpendente"];
            }

            // Retorna sucesso ou erro
            $retorno['success'] = true;
            $retorno['mensagem'] = "Sucesso!";

            echo json_encode($data, JSON_UNESCAPED_UNICODE);

        }catch (Exception $e){
            $retorno['success'] = false;
            $retorno['mensagem'] = "Erro ao buscar os dados".$e;
            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
             // Retorna sucesso ou erro
        }
         
    }


//******************* GRÁFICO DESPESAS ********************************************************************************
    /* 
    Retorna um JSON para alimentar os dados do gráfico de despesas
    */

    if($varFuncao == "graficoDespesas"){

        //$usuario = $_POST['usuario'];
        //$data = $_POST['data'];
        // Falta passar os parametros da data e do usuario

        $usuario = 62;
        $data = "2021/04/01";
       
        try{
            $query =  $db_con->query("SELECT fn_despesas.descricao as descricao, (CASE WHEN quitado = 'NÃO' THEN valorpendente ELSE valorquitado END) AS valorpendente 
                                    FROM fn_despesas_parcelas
                                    INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id
                                    WHERE usuarios_id = ".$usuario."
                                    AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')");

            $data = [];

            foreach ($query as $value) {
                $data[$value['descricao']] = $value["valorpendente"];
            }

            // Retorna sucesso ou erro
            $retorno['success'] = true;
            $retorno['mensagem'] = "Sucesso!";

            echo json_encode($data, JSON_UNESCAPED_UNICODE);

        }catch (Exception $e){
            $retorno['success'] = false;
            $retorno['mensagem'] = "Erro ao buscar os dados".$e;
            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
             // Retorna sucesso ou erro
        }
         
    }


//******************* BUSCAR DADOS DESPESA ****************************************************************************
    /* 
    Retorna um JSON para alimentar os dados da despesa
    */

    if($varFuncao == "buscarDadosDespesa"){

        $usuario = $_POST['userID'];
        $data = $_POST['dataParametro'];
        $id = $_POST['id'];
       
        try{
            $query =  $db_con->query("SELECT *
                                    FROM fn_despesas_parcelas
                                    INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id
                                    WHERE usuarios_id = ".$usuario."
                                    AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')");

            // Retorna sucesso ou erro
            $retorno['success'] = true;
            $retorno['mensagem'] = "Sucesso!";

            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);

        }catch (Exception $e){
            $retorno['success'] = false;
            $retorno['mensagem'] = "Erro ao buscar os dados".$e;
            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
             // Retorna sucesso ou erro
        }
         
    }

//******************* INCLUIR NOVA DESPESA ****************************************************************************
    /* 
    Retorna um JSON para alimentar os dados da despesa
    */

    if($varFuncao == "incluirDespesa"){

        //Recebe os dados do arrayCabecalhoDespesa
        $userID = $_POST['arrayDespesa'][0]['userID'];
        $descricao = $_POST['arrayDespesa'][0]['descricao'];

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
            $retorno['success'] = false;
            $retorno['mensagem'] = "Erro ao cadastrar despesa! - ".$e;
            echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
            exit;
        }
       
        //Salva os dados da Despesa no banco de dados
        try{
            $sql =  $db_con->query("INSERT INTO `fn_despesas` (`id`,`descricao`,`usuarios_id`) VALUES ('{$IDfndespesas}','{$descricao}','{$userID}')");
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

    }
    

//*********************************************************************************************************************

    function tempo_corrido($time) {

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
    }

 ?>