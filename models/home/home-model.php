<?php

/**
 * Classe com as funções da tela inicial e também das páginas relacionadas no controler: 'home-controller'
 *
 * @package TutsupMVC
 * @since 0.1
 */

class homeModel{

	/**
	 * $form_data
	 *
	 * Os dados do formulário de envio.
	 *
	 * @access public
	 */
	public $form_data;



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
	public function __construct($db = false){
		$this->db = $db;
	}

	/**
	 * Altera a senha do usuário logado
	 */
	public function alterar_senha(){
		// Configura os dados do formulário( não utilizado )
		$this->form_data = array();

		// Verifica se algo foi postado
		if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {

			//RECEBE OS DADOS via POST
			$id = $_SESSION["userdata"]["id"];
			$senhaAntiga = filter_input(INPUT_POST, 'senhaAntiga');
			$novaSenha = filter_input(INPUT_POST, 'novaSenha');
			$confirmacao = filter_input(INPUT_POST, 'confirmacao');

			// Precisaremos de uma instância da classe Phpass
			// veja http://www.openwall.com/phpass/
			$password_hash = new PasswordHash(8, FALSE);

			// Cria o hash da senha
			$senhaAntiga = $password_hash->HashPassword($senhaAntiga);
			$novaSenhaCriptografada = $password_hash->HashPassword($novaSenha);
		} else {
			// Termina se nada foi enviado
			return;
		}

		// Faz as validações
		if ($novaSenha !== $confirmacao) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  						<strong>Erro!</strong> A senha informada ' . $novaSenha . ' não coincide com a confirmação ' . $confirmacao . '.
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
			return;
		}

		// Faz a validação dos dados, para ver se a senha informada coincide com a senha antiga
		/* $validar = $this->db->query("SELECT senha FROM `usuarios` WHERE id = ".$id);
		while($row = $validar->fetch()) {
        	if($row['senha'] <> $senhaAntiga){
       			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  						<strong>Erro!</strong> A senha antiga informada'. $senhaAntiga.', não coincide com a senha salva nos nossos registros'.$row['senha'].'.
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
				return;	
        	}  	
    	} */

		// Atualiza a senha
		$query = $this->db->update('usuarios', 'id', $id, array(
			'senha' => $novaSenhaCriptografada,
		));

		// Verifica se a consulta está OK e configura a mensagem
		if (!$query) {
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
			echo  '<div class="alert alert-success alert-dismissible fade show" role="alert">
  						Senha alterada com sucesso!
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';

			// Termina
			return;
		}
	} // Alterar Senha

	/**
	 * Carrega uma lista com os dados do usuário logado
	 */
	public function verDadosMinhaConta($idUsuario){
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `usuarios` WHERE `id` = ' . $idUsuario);

		// Verifica se a consulta está OK
		if (!$query) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	} // verDadosMinhaConta


	//********************************************************************************************************************

	/**
	 * Envia a imagem
	 *
	 * @since 0.1
	 * @access public
	 */
	public function upload_imagem()
	{

		// Verifica se o arquivo da imagem existe
		if (empty($_FILES['imagemCliente'])) {
			$this->form_msg = '<p class="error">Imagem vazia.</p>';
			return;
		}

		// Configura os dados da imagem
		$imagem         = $_FILES['imagemCliente'];

		// Nome e extensão
		$nome_imagem    = strtolower($imagem['name']);
		$ext_imagem     = explode('.', $nome_imagem);
		$ext_imagem     = end($ext_imagem);
		$nome_imagem    = preg_replace('/[^a-zA-Z0-9]/', '', $nome_imagem);
		$nome_imagem   .= '_' . mt_rand() . '.' . $ext_imagem;

		// Tipo, nome temporário, erro e tamanho
		$tipo_imagem    = $imagem['type'];
		$tmp_imagem     = $imagem['tmp_name'];
		$erro_imagem    = $imagem['error'];
		$tamanho_imagem = $imagem['size'];

		// Os mime types permitidos
		$permitir_tipos  = array(
			'image/bmp',
			'image/x-windows-bmp',
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
		);

		// Verifica se o mimetype enviado é permitido
		if (!in_array($tipo_imagem, $permitir_tipos)) {
			// Retorna uma mensagem
			$this->form_msg = '<p class="error">Você deve enviar uma imagem.</p>';
			$imagemVazia = true;
			return;
		}

		// Tenta mover o arquivo enviado
		if (!move_uploaded_file($tmp_imagem, UP_ABSPATH . '/usuarios/' . $nome_imagem)) {
			// Retorna uma mensagem
			$this->form_msg = '<p class="error">Erro ao enviar imagem.</p>';
			return;
		}

		// Retorna o nome da imagem
		return $nome_imagem;
	} // upload_imagem

}
