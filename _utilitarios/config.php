<?php
/**
 * Configuração geral
 */
$tipo_conexao = $_SERVER['HTTP_HOST'];


// Caminho para a raiz
define( 'ABSPATH', dirname( __FILE__ ) );

// Caminho para a pasta de uploads (esse site não utiliza pasta de uploads, mas futuramente podera ter outra pasta padrao)
define( 'UP_ABSPATH', ABSPATH . '/views/_images' );

if($tipo_conexao == "localhost"){
	// URL da home
	define( 'HOME_URI', 'http://localhost:80/project/project/' );

	// Nome do host da base de dados
    define( 'HOSTNAME', 'localhost' );

    // Nome do DB
    define( 'DB_NAME', 'zeta' );

    // Usuário do DB
    define( 'DB_USER', 'root' );

    // Senha do DB
    define( 'DB_PASSWORD', '' );

    // Se você estiver desenvolvendo, modifique o valor para true
	define( 'DEBUG', true );

}else{
	// URL da home
	define( 'HOME_URI', "/atendimentos/" );
	//define( 'HOME_URI', 'http://localhost:80/project/project/' );

	// Nome do host da base de dados
	define( 'HOSTNAME', "192.185.176.177" );

	// Nome do DB
	define( 'DB_NAME', "alemte27_zeta" );

	// Usuário do DB
	define( 'DB_USER', "alemte27_alem" );

	// Senha do DB
	define( 'DB_PASSWORD', "a8LB9u17dg" );

	// Se você estiver desenvolvendo, modifique o valor para true
	define( 'DEBUG', false );
	

}

// Charset da conexão PDO
define( 'DB_CHARSET', "utf8" );

function ForceHTTPS() {
	if ($_SERVER[‘HTTPS’] != "on") {
		$url = $_SERVER[‘SERVER_NAME’];
		
		$new_url = "https://" . $url . $_SERVER[‘REQUEST_URI’];
		header("Location: $new_url");
		exit;
	}
}

/**
 * Não edite daqui em diante
 */

// Carrega o loader, que vai carregar a aplicação inteira
require_once ABSPATH . '/loader.php';
?>