<?php

/**
 * Usuário - CRUD de Usuários
 *
 * @package TutsupMVC
 * @since 0.1
 */
class usuarioController extends MainController{

	/**
	 * $login_required
	 *
	 * Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = false;

	/**
	 * Página principal do cadastro de usuários
	 */
	public function index(){
		// Título da página
		$this->title = 'Usuários | Zeta';
		// Parâmetros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('usuario/usuario-model');

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
		require ABSPATH . '/views/usuario/usuario-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // index

	/**
	 * Cadastro de novo usuário
	 */
	public function novo(){
		// Título da página
		$this->title = 'Novo Usuário';
		// Parâmetros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('usuario/usuario-model');

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
		require ABSPATH . '/views/usuario/novo-usuario-view.php';
		// rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // novo

	/**
	 * Edita os dados do usuário já cadastrado
	 */
	public function editar(){
		// Título da página
		$this->title = 'Editar Usuário';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('usuario/usuario-model');

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
		require ABSPATH . '/views/usuario/editar-usuario-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // editar

	/**
	 * Visializa os dados do usuário já cadastrado
	 */
	public function visualizar(){
		// Título da página
		$this->title = 'Visualizar Usuário';
		// Parametros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('usuario/usuario-model');

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
		require ABSPATH . '/views/usuario/visualizar-usuario-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // visualizar

} // class HomeController