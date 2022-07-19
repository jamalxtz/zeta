<?php
/**
 * Receitas - Controller das Receitas
 *
 * @package TutsupMVC
 * @since 0.1
 */
class ReceitasController extends MainController{
	/**
	 * Página inicial das receitas, exibe o resumo das receitas mensais e dá acesso aos outros menus de receitas
	 */
	public function index(){
		// Título da página
		$this->title = 'Receitas | Zeta';
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
		require ABSPATH . '/views/finances/fn-receitas-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Index Depesas 

	public function incluir(){
		// Título da página
		$this->title = 'Receitas - Incluir | Zeta';
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
		require ABSPATH . '/views/finances/fn-receitas-incluir-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Incluir Receita

	public function editar(){
		// Título da página
		$this->title = 'Receitas - Editar | Zeta';
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
		require ABSPATH . '/views/finances/fn-receitas-editar-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Editar Receita

} // class ReceitasController