<?php 
  $online = "localhost";

  if($online == "localhost"){
    // Conexão com o banco de dados online(servidor)
    $db_host = "localhost";
    $db_name = 'zeta_finances';
    $db_user = 'root';
    $db_pass = '';
  }
  else{

    // Conexão com o banco de dados online(servidor)
    $db_host = "162.241.2.89";
    $db_name = "ntolog02_zeta";
    $db_user = "ntolog02_nto";
    $db_pass = "3Akx97e0Fm";

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