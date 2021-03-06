<?php

/**
 * Despesas - Controller das Despesas
 *
 * @package TutsupMVC
 * @since 0.1
 */
class DespesasController extends MainController{
	/**
	 * Página inicial das despesas, exibe o resumo das despesas mensais e dá acesso aos outros menus de despesas
	 */
	public function index(){
		// Título da página
		$this->title = 'Despesas | Zeta';
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
			"<script src='" . HOME_URI . "views/_js/finances.js'></script>"
		);

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/finances/fn-despesas-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Index Depesas 

	public function incluir(){
		// Título da página
		$this->title = 'Despesas - Incluir | Zeta';
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
			"<script src='" . HOME_URI . "views/_js/finances.js'></script>"
		);

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/finances/fn-despesas-incluir-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Incluir Despesa

	public function editar(){
		// Título da página
		$this->title = 'Despesas - Editar | Zeta';
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
			"<script src='" . HOME_URI . "views/_js/finances.js'></script>"
		);

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/finances/fn-despesas-editar-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Editar Despesa

	public function selecionar(){
		// Título da página
		$this->title = 'Despesas - Selecionar | Zeta';
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
			"<script src='" . HOME_URI . "views/_js/finances.js'></script>"
		);

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/finances/fn-despesas-selecionar-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Selecionar Despesa

} // class DespesasController