<?php

/**
 * LoginController - Controller da página de login
 *
 * @package TutsupMVC
 * @since 0.1
 */
class LoginController extends MainController{

	/**
	 * Página de Login
	 */
	public function index(){
		// Título da página
		$this->title = 'Login | Zeta';
		// Parâmetros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

		// Login não tem Model

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		// require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/login/login-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Login

	/**
	 * Página de Registro
	 */
	public function registro(){
		// Título da página
		$this->title = 'Registro | Zeta';
		// Parâmetros da função
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		// Carrega o modelo para este view
		$modelo = $this->load_model('usuario/usuario-model');

		/** Carrega os arquivos do view **/
		// Header
		require ABSPATH . '/views/_includes/header.php';
		// Menu
		// require ABSPATH . '/views/_includes/menu.php';
		// Corpo da página
		require ABSPATH . '/views/login/registro-view.php';
		// Rodapé
		require ABSPATH . '/views/_includes/footer.php';
	} // Registro

} // class LoginController