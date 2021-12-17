<?php
/**
 * home - Controller de exemplo
 *
 * @package TutsupMVC
 * @since 0.1
 */
class FinancesController extends MainController
{

	/**
	 * Carrega a página "/views/home/index.php"
	 */
    public function dashboard() {
		// Título da página
		$this->title = 'Finances | Zeta';

		// Elementos do header especificos para essa página
		$this->elementosHeader = array( "foo",
										"bar",
										"hello",
										"world");
										
		// Elementos do footer especificos para essa página
		$this->elementosFooter = array( "<script src='".HOME_URI."views/_js/chart/graficos-finances.js'></script>");
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	

		// Carrega o modelo para este view
        $modelo = $this->load_model('finances/finances-model');
		
		/** Carrega os arquivos do view **/

		// Verifica se o usuário está logado
		if ( ! $this->logged_in ) {
		
			// Se não; garante o logout
			$this->logout();
			
			// Redireciona para a página de login
			$this->goto_login();
			
			// Garante que o script não vai passar daqui
			return;
		
		}

		
		// /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';
		
		// /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
		
		// /views/home/home-view.php
        require ABSPATH . '/views/finances/fn-dashboard-view.php';
		
		// /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
		
    } // index

		public function index() {
			$this->dashboard();
		}

	public function receitas() {
		// Título da página
		$this->title = 'Receitas | Zeta';

		// Elementos do header especificos para essa página
		$this->elementosHeader = array( "foo",
										"bar",
										"hello",
										"world");
										
		// Elementos do footer especificos para essa página
		$this->elementosFooter = array( "<script src='".HOME_URI."views/_js/chart/graficos-finances.js'></script>");
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();

		// Carrega o modelo para este view
        $modelo = $this->load_model('finances/finances-model');
		
				// Verifica se o usuário está logado
		if ( ! $this->logged_in ) {
		
			// Se não; garante o logout
			$this->logout();
			
			// Redireciona para a página de login
			$this->goto_login();
			
			// Garante que o script não vai passar daqui
			return;
		
		}

		/** Carrega os arquivos do view **/
		
		// /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';
		
		// /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
		
		// /views/home/home-view.php
        require ABSPATH . '/views/finances/fn-receitas-view.php';
		
		// /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
		
    } // Receitas  

	public function despesas() {
		// Título da página
		$this->title = 'Despesas | Zeta';

		// Elementos do header especificos para essa página
		$this->elementosHeader = array( "foo",
										"bar",
										"hello",
										"world");
										
		// Elementos do footer especificos para essa página
		$this->elementosFooter = array( "<script src='".HOME_URI."views/_js/finances.js'></script>",
																		"<script src='".HOME_URI."views/_js/chart/graficos-finances.js'></script>");
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();

		// Carrega o modelo para este view
        $modelo = $this->load_model('finances/finances-model');
		
				// Verifica se o usuário está logado
		if ( ! $this->logged_in ) {
		
			// Se não; garante o logout
			$this->logout();
			
			// Redireciona para a página de login
			$this->goto_login();
			
			// Garante que o script não vai passar daqui
			return;
		
		}

		/** Carrega os arquivos do view **/
		
		// /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';
		
		// /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
		
		// /views/home/home-view.php
        require ABSPATH . '/views/finances/fn-despesas-view.php';
		
		// /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
		
    } // Despesas 
	
} // class FinancesController