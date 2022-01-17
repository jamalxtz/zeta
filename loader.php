<?php
// Evita que usuários acesse este arquivo diretamente
if ( ! defined('ABSPATH')) exit;
 
// Inicia a sessão
session_set_cookie_params(2419200,"/"); // Essa configuração mantem o cookie salvo no navegador por um mês, sem precisar ter que ficar fazendo login o tempo todo
session_start();

// Verifica o modo para debugar
if ( ! defined('DEBUG') || DEBUG === false ) {

	// Esconde todos os erros
	error_reporting(0);
	ini_set("display_errors", 0); 
	
} else {

	// Mostra todos os erros
	error_reporting(E_ALL);
	ini_set("display_errors", 1); 
	
}

// Funções globais
require_once ABSPATH . '/functions/global-functions.php';

// Carrega a aplicação
$tutsup_mvc = new TutsupMVC();

