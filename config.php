<?php
/**
 * Configuração geral
 */
//$tipo_conexao = "site";
$tipo_conexao = $_SERVER['HTTP_HOST'];
define( 'TIPO_CONEXAO', $_SERVER['HTTP_HOST'] );


// Caminho para a raiz
define( 'ABSPATH', dirname( __FILE__ ) );

// Caminho para a pasta de uploads (esse site não utiliza pasta de uploads, mas futuramente podera ter outra pasta padrao)
define( 'UP_ABSPATH', ABSPATH . '/views/_images' );

define( 'UP_DOWNLOAD_ABSPATH', ABSPATH . '/views/_downloads' );


if($tipo_conexao == "localhost"){
	// URL da home
	define( 'HOME_URI', 'http://localhost/zeta/zeta/' );

	// URL do site
	define( 'SITE_URI', "http://localhost/zeta/zeta/" );

	// Nome do host da base de dados
  define( 'HOSTNAME', 'localhost' );

  // Nome do DB
  define( 'DB_NAME', 'zeta_finances' );

  // Usuário do DB
  define( 'DB_USER', 'root' );

  // Senha do DB
  define( 'DB_PASSWORD', '' );

  // Se você estiver desenvolvendo, modifique o valor para true
	define( 'DEBUG', true );

}else{
	// URL da home
	//define( 'HOME_URI', "http://br972.teste.website/~icomet77/siteadmin/" );
	define( 'HOME_URI', "/zeta/" );

	// URL do site
	//define( 'SITE_URI', "http://icometais.com.br/" );

	// Nome do host da base de dados
	//define( 'HOSTNAME', 'localhost' );
	define( 'HOSTNAME', "sql212.epizy.com" );

	// Nome do DB
	//define( 'DB_NAME', 'zeta' );
	define( 'DB_NAME', "epiz_32245153_zeta" );

	// Usuário do DB
	//define( 'DB_USER', 'root' );
	define( 'DB_USER', "epiz_32245153" );

	// Senha do DB
	//define( 'DB_PASSWORD', '' );
	define( 'DB_PASSWORD', "1WcludFAI6" );

	// Se você estiver desenvolvendo, modifique o valor para true
	define( 'DEBUG', true );

}

// Charset da conexão PDO
define( 'DB_CHARSET', "utf8" );



/**
 * Não edite daqui em diante
 */

// Carrega o loader, que vai carregar a aplicação inteira
require_once ABSPATH . '/loader.php';
?>