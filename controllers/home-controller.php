<?php

/**
 * home - Controller da Página Inicial
 *
 * @package TutsupMVC
 * @since 0.1
 */
class HomeController extends MainController{

	/**
	 * Dashboard - Página inicial, exibida logo após o login
	 */
	public function index(){
		// Título da página
		$this->title = 'Dashboard | Zeta';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('finances/finances-model');

		// Verifica se o usuário está logado
		if (!$this->logged_in) {
			// Se não; garante o logout
			$this->logout();
			// Redireciona para a página de login
			$this->goto_login();
			// Garante que o script não vai passar daqui
			return;
		}

		// Elementos do header especificos para essa página
		$this->elementosHeader = array(
			"foo",
			"bar",
			"hello",
			"world"
		);
		// Elementos do footer especificos para essa página
		$this->elementosFooter = array(
			"<script src='" . HOME_URI . "views/_js/finances.js'></script>",
			"<script src='" . HOME_URI . "views/_js/chart/graficos-finances.js'></script>"
		);

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/finances/fn-dashboard-view.php';
		// Footer
		require ABSPATH . '/views/_includes/footer.php';
	} // Dashboard

//--OUTRAS PÁGINAS-----------------------------------------------------------------------------------------------------

	/**
	 * Página de configuração da conta do usuário logado
	 */
	public function conta(){
		// Título da página
		$this->title = 'Painel | Minha Conta';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modeloMinhaConta = $this->load_model('home/home-model');

		// Verifica se o usuário está logado
		if (!$this->logged_in) {
			// Se não; garante o logout
			$this->logout();
			// Redireciona para a página de login
			$this->goto_login();
			// Garante que o script não vai passar daqui
			return;
		}

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da Página
		require ABSPATH . '/views/home/conta-view.php';
		// Footer
		require ABSPATH . '/views/_includes/footer.php';
	} // Minha Conta

	/**
	 * Página de Configurações gerais do site
	 */
	public function configuracoes(){
		// Título da página
		$this->title = 'Painel | Configurações';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modeloConfiguracoes = $this->load_model('home/home-model');

		// Verifica se o usuário está logado
		if (!$this->logged_in) {
			// Se não; garante o logout
			$this->logout();
			// Redireciona para a página de login
			$this->goto_login();
			// Garante que o script não vai passar daqui
			return;
		}

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da Página
		require ABSPATH . '/views/home/configuracoes-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Configurações

	/**
	 * Página de Ajuda, contém dicas e informações sobre o funcionamento do sistema.
	 */
	public function ajuda(){
		// Título da página
		$this->title = 'Painel | Ajuda';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modeloAjuda = $this->load_model('home/home-model');

		// Verifica se o usuário está logado
		if (!$this->logged_in) {
			// Se não; garante o logout
			$this->logout();
			// Redireciona para a página de login
			$this->goto_login();
			// Garante que o script não vai passar daqui
			return;
		}

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/home/ajuda-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Ajuda

	/**
	 * Ao ser chamado, essa função faz o logout do usuário e redireciona para a página de login
	 */
	public function sair(){
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Faz o logout do usuário
		$this->logout();
		// Redireciona para a página de login
		$this->goto_login();
		// Garante que o script não vai passar daqui
		return;
	} //Sair

} // class HomeController