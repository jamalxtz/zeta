<?php 
/**
 * Classe com as funções do finances
 *
 * @package TutsupMVC
 * @since 0.1
 */
class FinancesModel extends MainModel{

	/**
	 * $form_data
	 *
	 * Os dados do formulário de envio.
	 *
	 * @access public
	 */	
	public $form_data;
	
	public $retorno;
	
	/**
	 * $form_msg
	 *
	 * As mensagens de feedback para o usuário.
	 *
	 * @access public
	 */	
	public $form_msg;

	/**
	 * $db
	 *
	 * O objeto da nossa conexão PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * Construtor
	 * 
	 * Carrega  o DB.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function __construct( $db = false ) {
		$this->db = $db;
	}

//********** FUNÇÕES DE RECEITAS ******************************************************************/
	public function incluir_receita () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'incluirReceitaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "incluirReceita" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$descricao = filter_input(INPUT_POST, 'NRdescricao', FILTER_SANITIZE_SPECIAL_CHARS);
			$valorPendente = filter_input(INPUT_POST, 'NRvalor');
			$vencimento = filter_input(INPUT_POST, 'NRvencimento');
			//$fixo = filter_input(INPUT_POST, 'NRfixo');
			//$valorQuitado = filter_input(INPUT_POST, 'valorQuitado');
			//$quitado = filter_input(INPUT_POST, 'quitado', FILTER_SANITIZE_SPECIAL_CHARS);
			//$quitacao = filter_input(INPUT_POST, 'quitacao');
      $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}

		// Formata a descrição 'ucwords' transforma a primeira letra de cada palavra para maiuscula
		$descricao = ucwords($descricao);

		/*if(empty($rg)){
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  						<strong>Erro!</strong>rg incorreto.
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
			return;
		}*/

		// Verifica se a receita é fixa ou nao.
		/* if($fixo){
			$fixo = "SIM";
		}
		else
		{
			$fixo = "NÃO";
		} */

		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00)
		$valorPendente = str_replace(' ','',$valorPendente);
		$valorPendente = str_replace('.','',$valorPendente);
		$valorPendente = str_replace(',','.',$valorPendente);
		//$valorPendente =  doubleval($valorPendente);

		//verifica se tem id, se tiver ele apenas faz o update dos dados
		if($id !== null){
			$query = $this->db->update('fn_receitas', 'id', $id, array(
				'descricao' => $descricao, 
				'valorpendente' => $valorPendente,
				'vencimento' => $vencimento,  
				'fixo' => $fixo,
				'quitado' => 'NÃO',
				'usuarios_id' => $usuario
			));

		} else {
		
			$query = $this->db->insert('fn_receitas', array(
				'descricao' => $descricao, 
				'valorpendente' => $valorPendente,
				'vencimento' => $vencimento,  
				'fixo' => $fixo,
				'quitado' => 'NÃO',
				'usuarios_id' => $usuario
			));

		}

		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			// echo $this->form_msg;
			/*echo  '<div class="alert alert-success alert-dismissible fade show" role="alert">
			Atendimento cadastrado com sucesso!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>';*/
			if($id !== null){
				echo '<script>alert("Receita alterada com sucesso!");</script>';
			}else{
				echo '<script>alert("Receita cadastrada com sucesso!");</script>';
			}

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/receitas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/receitas";</script>';
		
			// Termina
			return;
		}
	} // Fim incluir receita


	public function quitar_receita () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'quitarReceitaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "quitarReceita" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$valorQuitado = filter_input(INPUT_POST, 'QRvalorquitado');
			$quitacao = filter_input(INPUT_POST, 'QRquitacao');
            $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}


		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00)
		$valorQuitado = str_replace('.','',$valorQuitado);
		$valorQuitado =  intval($valorQuitado);

		// Faz o update dos dados
		$query = $this->db->update('fn_receitas', 'id', $id, array(
			'valorquitado' => $valorQuitado, 
			'quitacao' => $quitacao,
			'quitado' => 'SIM'
		));


		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Receita quitada com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/receitas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/receitas";</script>';
		
			// Termina
			return;
		}
	} // Fim Quitar Receita

	public function estornar_receita () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'estornarReceitaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "estornarReceita" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$valorQuitado = filter_input(INPUT_POST, 'QRvalorquitado');
			$quitacao = filter_input(INPUT_POST, 'QRquitacao');
            $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}


		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00), se trocar o 1 pelo 2 a função faz a operação inverso
		$valorQuitado =  number_format(intval($valorQuitado), 1);

		// Faz o update dos dados
		$query = $this->db->update('fn_receitas', 'id', $id, array(
			'valorquitado' => "0.00", 
			'quitacao' => NULL,
			'quitado' => 'NÃO'
		));


		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Receita estornada com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/receitas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/receitas";</script>';
		
			// Termina
			return;
		}
	} // Fim Quitar Receita

	
	/**
	 * Obtém a lista de usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function listar_receitas( $parametros = array() ) {

		$usuario = $_SESSION["userdata"]["id"];

		$mes = null;
		$ano = null;

		// Pega o mês e o ano dos parâmetros da URL
		$mes = chk_array( $parametros, 0 );
		$ano = chk_array( $parametros, 1 );

		$data = $ano."/".$mes."/01";
		
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query("SELECT fn_receitas.descricao AS descricao,
																			COALESCE(SUM(fn_receitas_parcelas.id),0) as qtde_parcelas,
																			COALESCE(SUM(fn_receitas_parcelas.valorpendente),0) as vlr_parcelas_pendentes,
																			COALESCE(SUM(fn_receitas_parcelas.valorquitado),0) as vlr_parcelas_quitadas
															FROM fn_receitas_parcelas
																	INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id
															WHERE usuarios_id = ".$usuario."
																	AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')");
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados
		return $query->fetchAll();
	} // Função utilizada para pegar lista de Receitas


	// Lista o valor total das receitas
	public function listar_total_receitas(  $parametros = array()  ) {
		
		$usuario = $_SESSION["userdata"]["id"];

		$mes = null;
		$ano = null;

		// Pega o mês e o ano dos parâmetros da URL
		$mes = chk_array( $parametros, 0 );
		$ano = chk_array( $parametros, 1 );

		$data = $ano."/".$mes."/01";

		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query("SELECT TotalPendente, TotalQuitado FROM 
																	(SELECT SUM(valorpendente)as TotalPendente 
																			FROM fn_receitas_parcelas 
																				INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id 
																			WHERE quitado = 'NÃO' AND usuarios_id = ".$usuario." 
																				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')) as sub,
																	(SELECT SUM(valorquitado)as TotalQuitado
																			FROM fn_receitas_parcelas 
																				INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id 
																			WHERE quitado = 'SIM' AND usuarios_id = ".$usuario."
																				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')) as sub1");
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do clientes
		return $query->fetchAll();
		

	} // FIM Lista o valor total das receitas
 

	/**
	 * Excluir Receitas
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function excluir_receita() {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'excluirReceitaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "excluirReceita" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}

		$query = $this->db->delete('fn_receitas', 'id', $id);

		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Receita excluida com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/receitas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/receitas";</script>';
		
			// Termina
			return;
		}

	} // Função utilizada para exluir receitas


//********** FUNÇÕES DE DESPESAS ******************************************************************/

	// Obsoleto, agora a inclusão de despesas é feito atraves de requisição Ajax
	public function incluir_despesa () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'incluirDespesaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "incluirDespesa" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$descricao = filter_input(INPUT_POST, 'NDdescricao', FILTER_SANITIZE_SPECIAL_CHARS);
			$valorPendente = filter_input(INPUT_POST, 'NDvalor');
			$vencimento = filter_input(INPUT_POST, 'NDvencimento');
			$fixo = filter_input(INPUT_POST, 'NDfixo');
			//$valorQuitado = filter_input(INPUT_POST, 'valorQuitado');
			//$quitado = filter_input(INPUT_POST, 'quitado', FILTER_SANITIZE_SPECIAL_CHARS);
			//$quitacao = filter_input(INPUT_POST, 'quitacao');
            $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}

		// Formata a descrição 'ucwords' transforma a primeira letra de cada palavra para maiuscula
		$descricao = ucwords($descricao);

		/*if(empty($rg)){
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  						<strong>Erro!</strong>rg incorreto.
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
			return;
		}*/

		// Verifica se a despesa é fixa ou nao.
		if($fixo){
			$fixo = "SIM";
		}
		else
		{
			$fixo = "NÃO";
		}

		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00)
		$valorPendente = str_replace(' ','',$valorPendente);
		$valorPendente = str_replace('.','',$valorPendente);
		$valorPendente = str_replace(',','.',$valorPendente);

		//verifica se tem id, se tiver ele apenas faz o update dos dados
		if($id !== null){
			$query = $this->db->update('fn_despesas', 'id', $id, array(
				'descricao' => $descricao, 
				'valorpendente' => $valorPendente,
				'vencimento' => $vencimento,  
				'fixo' => $fixo,
				'quitado' => 'NÃO',
				'usuarios_id' => $usuario
			));

		} else {
		
			$query = $this->db->insert('fn_despesas', array(
				'descricao' => $descricao, 
				'valorpendente' => $valorPendente,
				'vencimento' => $vencimento,  
				'fixo' => $fixo,
				'quitado' => 'NÃO',
				'usuarios_id' => $usuario
			));

		}

		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			// echo $this->form_msg;
			/*echo  '<div class="alert alert-success alert-dismissible fade show" role="alert">
			Atendimento cadastrado com sucesso!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>';*/
			if($id !== null){
				echo '<script>alert("Despesa alterada com sucesso!");</script>';
			}else{
				echo '<script>alert("Despesa cadastrada com sucesso!");</script>';
			}

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/despesas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/despesas";</script>';
		
			// Termina
			return;
		}
	} // Fim incluir despesa

	public function quitar_despesa () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'quitarDespesaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "quitarDespesa" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$valorQuitado = filter_input(INPUT_POST, 'QDvalorquitado');
			$quitacao = filter_input(INPUT_POST, 'QDquitacao');
            $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}

		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00)
		$valorQuitado = str_replace('.','',$valorQuitado);
		$valorQuitado =  intval($valorQuitado);

		// Faz o update dos dados
		$query = $this->db->update('fn_despesas', 'id', $id, array(
			'valorquitado' => $valorQuitado, 
			'quitacao' => $quitacao,
			'quitado' => 'SIM'
		));


		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Despesa quitada com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/despesas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/despesas";</script>';
		
			// Termina
			return;
		}
	} // Fim Quitar Despesa

	public function estornar_despesa () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'estornarDespesaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "estornarDespesa" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$valorQuitado = filter_input(INPUT_POST, 'QDvalorquitado');
			$quitacao = filter_input(INPUT_POST, 'QDquitacao');
            $usuario = $_SESSION["userdata"]["id"];

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}


		// Formarta o valor por um valor aceito pelo banco de dados ex (2,575,00 | 2575.00), se trocar o 1 pelo 2 a função faz a operação inverso
		$valorQuitado =  number_format(intval($valorQuitado), 1);

		// Faz o update dos dados
		$query = $this->db->update('fn_despesas', 'id', $id, array(
			'valorquitado' => "0.00", 
			'quitacao' => NULL,
			'quitado' => 'NÃO'
		));


		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Despesa estornada com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/despesas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/despesas";</script>';
		
			// Termina
			return;
		}
	} // Fim Quitar Despesa

	
	/**
	 * Obtém a lista de usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function listar_despesas(  $parametros = array()  ) {

		$usuario = $_SESSION["userdata"]["id"];

		$mes = null;
		$ano = null;

		// Pega o mês e o ano dos parâmetros da URL
		$mes = chk_array( $parametros, 0 );
		$ano = chk_array( $parametros, 1 );

		$data = $ano."/".$mes."/01";

		$SQL = "SELECT fn_despesas_parcelas.id,
							fn_despesas.descricao,
							fn_despesas_parcelas.valorpendente,
							fn_despesas_parcelas.valorquitado,
							fn_despesas_parcelas.quitado
						FROM 	fn_despesas_parcelas
							INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id = fn_despesas.id
						WHERE	usuarios_id = ".$usuario."
							AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')";
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query($SQL);
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do clientes
		return $query->fetchAll();
	} // Função utilizada para pegar lista de clientes


	// Lista o valor total das despesas
	public function listar_total_despesas(  $parametros = array()  ) {
	
		$usuario = $_SESSION["userdata"]["id"];

		$mes = null;
		$ano = null;

		// Pega o mês e o ano dos parâmetros da URL
		$mes = chk_array( $parametros, 0 );
		$ano = chk_array( $parametros, 1 );

		$data = $ano."/".$mes."/01";


		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query("SELECT TotalPendente, TotalQuitado FROM 
																	(SELECT SUM(valorpendente)as TotalPendente 
																			FROM fn_despesas_parcelas 
																				INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id 
																			WHERE quitado = 'NÃO' AND usuarios_id = ".$usuario." 
																				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')) as sub,
																	(SELECT SUM(valorquitado)as TotalQuitado
																			FROM fn_despesas_parcelas 
																				INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id 
																			WHERE quitado = 'SIM' AND usuarios_id = ".$usuario."
																				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m')) as sub1");
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados
		return $query->fetchAll();
		
	} // FIM Lista o valor total das despesas
 

	/**
	 * Excluir Despesas
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function excluir_despesa() {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		$acao = filter_input(INPUT_POST, 'excluirDespesaBTN');

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) && $acao == "excluirDespesa" ) {
		
			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		} else {
		
			// Termina se nada foi enviado
			return;
			
		}

		$query = $this->db->delete('fn_despesas', 'id', $id);

		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> os dados não foram enviados.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>';
			// Termina
			return;
		} else {

			echo '<script>alert("Despesa excluida com sucesso!");</script>';

			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">finances/despesas';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/finances/despesas";</script>';
		
			// Termina
			return;
		}

	} // Função utilizada para exluir despesas


//********** FUNÇÕES DO DASHBOARD *****************************************************************/


	// Lista o valor total do resultado
	public function listar_total_resultado(  $parametros = array()  ) {

		$usuario = $_SESSION["userdata"]["id"];

		$mes = null;
		$ano = null;

		// Pega o mês e o ano dos parâmetros da URL
		$mes = chk_array( $parametros, 0 );
		$ano = chk_array( $parametros, 1 );

		$data = $ano."/".$mes."/01";

		$SQL = "SELECT  (TReceitasPendente - TDespesasPendente) as ResultadoPendente,
										(TReceitasQuitado - TDespesasQuitado) as ResultadoQuitado
						FROM 
									(SELECT COALESCE(SUM(valorpendente),0) as TReceitasPendente 
											FROM fn_receitas_parcelas
												INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id
											WHERE quitado = 'NÃO' AND usuarios_id = ".$usuario."
												AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m') ) as sub,
											
									(SELECT COALESCE(SUM(valorquitado),0) as TReceitasQuitado 
											FROM fn_receitas_parcelas
												INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id
											WHERE quitado = 'SIM' AND usuarios_id = ".$usuario."
												AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m') ) as sub1,
										
									(SELECT COALESCE(SUM(valorpendente),0) as TDespesasPendente 
											FROM fn_despesas_parcelas
												INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id
											WHERE quitado = 'NÃO' AND usuarios_id = ".$usuario."
												AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m') ) as sub2,
										
									(SELECT COALESCE(SUM(valorquitado),0) as TDespesasQuitado 
											FROM fn_despesas_parcelas
												INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id
											WHERE quitado = 'SIM' AND usuarios_id = ".$usuario."
												AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$data."','%Y-%m') ) as sub3";


		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query( $SQL );
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados
		return $query->fetchAll();
		
	} // FIM Lista o valor total do resultado


	// Seleciona o mês com base nas receitas e despesas que estão abertas
	public function selecionar_mes() {

		$usuario = $_SESSION["userdata"]["id"];
		// Traz a data atual
		date_default_timezone_set('America/Sao_Paulo');
		$dataAtual = date('Y/m/d');
		$mesAnterior = date('Y/m/d', strtotime('-1 months', strtotime(date('Y-m-d'))));
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query("SELECT qtdeDespesas, qtdeReceitas
			FROM
			(SELECT COUNT(*) AS qtdeDespesas
			FROM fn_despesas_parcelas 
				INNER JOIN fn_despesas ON fn_despesas_parcelas.fn_despesas_id= fn_despesas.id
			WHERE quitado = 'NÃO'
				AND fn_despesas.usuarios_id = '".$usuario."'
				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$mesAnterior."','%Y-%m')) AS sub1,
			(SELECT COUNT(*) AS qtdeReceitas
			FROM fn_receitas_parcelas 
				INNER JOIN fn_receitas ON fn_receitas_parcelas.fn_receitas_id= fn_receitas.id
			WHERE quitado = 'NÃO'
				AND fn_receitas.usuarios_id = '".$usuario."'
				AND DATE_FORMAT(vencimento,'%Y-%m') = DATE_FORMAT('".$mesAnterior."','%Y-%m')) AS sub2");
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}

		$resultado = $query->fetchAll();

		$qtdeReceitasMesAnterior = 0;
		$qtdeDespesasMesAnterior = 0;

		foreach ($resultado as $value) {
			$qtdeReceitasMesAnterior = $value['qtdeReceitas'];
			$qtdeDespesasMesAnterior = $value['qtdeDespesas'];
		}

		if($qtdeReceitasMesAnterior > 0 or  $qtdeDespesasMesAnterior > 0){
			return substr($mesAnterior,5,3).substr($mesAnterior,0,4)	;
		}else{
			return substr($dataAtual,5,3).substr($dataAtual,0,4);
		}

	} // FIM Seleciona o mês

//********** FUNÇÕES GLOBAIS **********************************************************************/
	/**
   * Obtém a lista de Categorias Financeiras
	 * Receita | Despesa | Investimento
   */
  public function listar_categorias( $tipo ) {

    $usuario = $_SESSION["userdata"]["id"];
		
    $SQL = "SELECT * FROM fn_categorias WHERE tipo = '".$tipo."' AND usuarios_id = '".$usuario."' ORDER BY descricao ASC";

    // Simplesmente seleciona os dados na base de dados
    $query = $this->db->query($SQL);
   
    // Verifica se a consulta está OK
    if ( ! $query ) {
      return array();
    }
    // Preenche a tabela com os dados das Categorias Financeiras
    return $query->fetchAll();
  } // Função utilizada para pegar lista de Categorias Financeiras

	public function formatar_valor ($valor) {
		if ($valor == NULL) $valor = 0;
		$valorFormatado = number_format($valor, 2, ',', ' ');
		return strval($valorFormatado);
	}

	public function formatar_data ($data) {
		/* $data = date('Y-m-d');
		return strval($data); */

		$data = date_create($data);
		return date_format($data, 'Y-m-d');
	}

	public function verifica_status ($quitado, $vencimento) {
		// Traz a data atual
		date_default_timezone_set('America/Sao_Paulo');
		$data = date('Y/m/d');
		$hora = date('h:i a');

	    $expiry_date = $vencimento;
        $today = date('d-m-Y',time()); 
        $exp = date('d-m-Y',strtotime($expiry_date));
        $expDate =  date_create($exp);
        $todayDate = date_create($today);
        $diff =  date_diff($todayDate, $expDate);
        /* if($diff->format("%R%a")>0){
            echo "active";
        }else{
            echo "inactive";
        }
        echo "Remaining Days ".$diff->format("%R%a days"); */


		if ($quitado == "SIM") {
			return "table-success";
		} elseif($quitado == "NÃO" && $diff->format("%R%a") < 0 ) {
			return "table-danger";
		} elseif($quitado == "NÃO" && $diff->format("%R%a") == 0 ) {
			return "table-warning";
		}else{
			return "";
		}
		
	}

} //FIM Classe/model
