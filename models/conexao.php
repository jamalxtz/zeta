<?php 
  $online = "site";

  if($online == "localhost"){
    // Conexão com o banco de dados online(servidor)
    $db_host = "localhost";
    $db_name = 'zeta_finances';
    $db_user = 'root';
    $db_pass = '';
  }
  else{

    // Conexão com o banco de dados online(servidor)
    $db_host = "192.185.176.177";
    $db_name = "alemte27_zeta_finances";
    $db_user = "alemte27_alem";
    $db_pass = "a8LB9u17dg";

  }
  try{
    
    $db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
    $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_con -> exec("SET CHARACTER SET utf8");
    //echo "conexao estabelecida com sucesso";
  }
  catch(PDOException $e){
    echo "erro ao conectar o banco de dados".$e->getMessage();
  }
 ?>