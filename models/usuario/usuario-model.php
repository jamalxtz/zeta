<?php

/**
 * Classe para registros de usuários
 *
 * @package TutsupMVC
 * @since 0.1
 */

class usuarioModel extends MainModel{

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
	public function __construct($db = false)
	{
		$this->db = $db;
	}

	/**
	 * Inclui ou altera um usuário cadastrado com base no campo 'id' do formulário
	 */
	public function cadastrar_usuario(){
		// Configura os dados do formulário( não utilizado )
		$this->form_data = array();

		// Verifica se algo foi postado
		if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {

			//RECEBE OS DADOS via POST
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$situacao = filter_input(INPUT_POST, 'situacao');
			$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
			$rg = filter_input(INPUT_POST, 'rg', FILTER_SANITIZE_SPECIAL_CHARS);
			$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
			$sobrenome = filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_SPECIAL_CHARS);
			$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
			$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
			$celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_SPECIAL_CHARS);

			$cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
			$logradouro = filter_input(INPUT_POST, 'logradouro', FILTER_SANITIZE_SPECIAL_CHARS);
			$complemento = filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_SPECIAL_CHARS);
			$bairro = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS);
			$cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS);
			$estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);

			$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);
			$confirma = filter_input(INPUT_POST, 'confirmar', FILTER_SANITIZE_SPECIAL_CHARS);
			$dica = filter_input(INPUT_POST, 'dica', FILTER_SANITIZE_SPECIAL_CHARS);

			$pAcessarAreaRestrita = filter_input(INPUT_POST, 'pAcessarAreaRestrita');
			$pAcessarSiteAdmin = filter_input(INPUT_POST, 'pAcessarSiteAdmin');
			$pAcessarCadastroUsuarios = filter_input(INPUT_POST, 'pAcessarCadastroUsuarios');


			date_default_timezone_set('America/Sao_Paulo');
			$cadastro = date('Y/m/d');

			//Salva todos os dados em uma session
			$_SESSION['CUcpf'] = $cpf;
			$_SESSION['CUrg'] = $rg;
			$_SESSION['CUnome'] = $nome;
			$_SESSION['CUsobrenome'] = $sobrenome;
			$_SESSION['CUemail'] = $email;
			$_SESSION['CUtelefone'] = $telefone;
			$_SESSION['CUcelular'] = $celular;

			$_SESSION['CUcep'] = $cep;
			$_SESSION['CUlogradouro'] = $logradouro;
			$_SESSION['CUcomplemento'] = $complemento;
			$_SESSION['CUbairro'] = $bairro;
			$_SESSION['CUcidade'] = $cidade;
			$_SESSION['CUestado'] = $estado;

			$_SESSION['CUsenha'] = $senha;
			$_SESSION['CUconfirma'] = $confirma;
			$_SESSION['CUdica'] = $dica;

			// Precisaremos de uma instância da classe Phpass
			// veja http://www.openwall.com/phpass/
			$password_hash = new PasswordHash(8, FALSE);

			// Cria o hash da senha
			$password = $password_hash->HashPassword($senha);

			// Verifica se tem imagem, caso tenha chama o método que salva o arquivo no servidor
			if ($_FILES['imagemCliente']['size'] == 0) {
				//$this->form_msg = '<p class="error">imagem vazia primeira validaçao.</p>';
				$imagem = "";
			} else {
				// Tenta enviar a imagem
				$imagem = $this->upload_imagem();
			}

			// Verifica se o usuario está ativo ou não.
			if (isset($situacao)) {
				$situacao = "Ativo";
			} else {
				$situacao = "Inativo";
			}

			// Verifica as permissoes
			$permissions = $pAcessarAreaRestrita . "," . $pAcessarSiteAdmin. "," . $pAcessarCadastroUsuarios;
			// Faz um trim nas permissões
			$permissions = array_map('trim', explode(',', $permissions));
			// Remove permissões duplicadas
			$permissions = array_unique($permissions);
			// Remove valores em branco
			$permissions = array_filter($permissions);
			// Serializa as permissões
			$permissions = serialize($permissions);
		} else {
			// Termina se nada foi enviado
			return;
		}

		// Faz as validações
		if ($senha !== $confirma) {
			echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  						<strong>Erro!</strong> A senha informada ' . $senha . ' não coincide com a confirmação ' . $confirma . '.
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
			return;
		}

		// Verifica se tem id, se tiver ele apenas faz o update dos dados
		if ($id !== null) {
			// EDITA USUÁRIO CADASTRADO
			// Verifica se o usuario alterou a imagem, se tiver alterado ele grava, caso nao tenha alteração ele grava apenas as outras informaçoes
			if ($_FILES['imagemCliente']['size'] == 0) {
				$query = $this->db->update('usuarios', 'id', $id, array(
					'cpf' => $cpf,
					'rg' => $rg,
					'nome' => $nome,
					'sobrenome' => $sobrenome,
					'email' => $email,
					'telefone' => $telefone,
					'celular' => $celular,
					'cep' => $cep,
					'logradouro' => $logradouro,
					'complemento' => $complemento,
					'bairro' => $bairro,
					'cidade' => $cidade,
					'estado' => $estado,
					'situacao' => $situacao,
					'user_permissions' => $permissions,
					'user'   => $email
				));
			} else {
				$query = $this->db->update('usuarios', 'id', $id, array(
					'cpf' => $cpf,
					'rg' => $rg,
					'nome' => $nome,
					'sobrenome' => $sobrenome,
					'email' => $email,
					'telefone' => $telefone,
					'celular' => $celular,
					'cep' => $cep,
					'logradouro' => $logradouro,
					'complemento' => $complemento,
					'bairro' => $bairro,
					'cidade' => $cidade,
					'estado' => $estado,
					'user'   => $email,
					'situacao'   => $situacao,
					'user_permissions' => $permissions,
					'imagem' => $imagem
				));
			} // Fim do else que verifica se a imagem foi alterada

		} else {
			// CADASTRA NOVO USUÁRIO
			// Faz a validação dos dados, para ver se o usuario já está cadastrado.
			$validar = $this->db->query("SELECT * FROM `usuarios` WHERE email LIKE '" . $email . "' or cpf LIKE '" . $cpf . "' ORDER BY id DESC");
			while ($row = $validar->fetch()) {
				if ($row['id']) {
					echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  						<strong>Erro!</strong> Usuário ' . $row['nome'] . ' já está cadastrado.
		  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    					<span aria-hidden="true">&times;</span>
		  						</button>
								</div>';
					return;
				}
			}

			// Executa o insert 
			$query = $this->db->insert('usuarios', array(
				'cpf' => $cpf,
				'rg' => $rg,
				'nome' => $nome,
				'sobrenome' => $sobrenome,
				'email' => $email,
				'telefone' => $telefone,
				'celular' => $celular,
				'cep' => $cep,
				'logradouro' => $logradouro,
				'complemento' => $complemento,
				'bairro' => $bairro,
				'cidade' => $cidade,
				'estado' => $estado,
				'senha' => $password,
				'dica' => $dica,
				'cadastro' => $cadastro,
				'user'   => $email,
				'situacao'   => "Ativo",
				'user_permissions' => 'AcessarAreaRestrita',
				'imagem' => $imagem
			));
		}
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
  						Usuário <strong>' . $nome . '</strong> cadastrado com sucesso!
  						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  						</button>
						</div>';
			// Caso o usuario seja cadastrado com sucesso, limpa os dados do usuario da session
			$_SESSION['CUcpf'] = "";
			$_SESSION['CUrg'] = "";
			$_SESSION['CUnome'] = "";
			$_SESSION['CUsobrenome'] = "";
			$_SESSION['CUemail'] = "";
			$_SESSION['CUtelefone'] = "";
			$_SESSION['CUcelular'] = "";

			$_SESSION['CUcep'] = "";
			$_SESSION['CUlogradouro'] = "";
			$_SESSION['CUcomplemento'] = "";
			$_SESSION['CUbairro'] = "";
			$_SESSION['CUcidade'] = "";
			$_SESSION['CUestado'] = "";

			$_SESSION['CUsenha'] = "";
			$_SESSION['CUconfirma'] = "";
			$_SESSION['CUdica'] = "";

			// Termina
			return;
		}
	} // cadastrar_usuario

	/**
	 * Obtém a lista de usuários
	 */
	public function pegar_lista_usuarios(){
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `usuarios` ORDER BY id DESC');
		// Verifica se a consulta está OK
		if (!$query) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	} // Função utilizada para pegar lista de usuarios

	/**
	 * Edita Usuários
	 */
	public function editar_usuario($parametros = array()){
		// O ID do usuário
		$user_id = null;
		// Verifica se existe o parâmetro "del" na URL
		if (chk_array($parametros, 0) == 'edit') {
			// Configura o ID do usuário a ser editado
			$user_id = chk_array($parametros, 1);
		}

		// Verifica se o ID não está vazio
		if (!empty($user_id)) {

			// O ID precisa ser inteiro
			$user_id = (int)$user_id;

			// Simplesmente seleciona os dados na base de dados 
			$query = $this->db->query('SELECT * FROM `usuarios` WHERE id = ' . $user_id . ' ORDER BY id DESC');

			// Verifica se a consulta está OK
			if (!$query) {
				echo  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Erro!</strong> Problemas ao consultar o BD.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
						</button>
					</div>';
				return array();
			}
			// Preenche a tabela com os dados do usuário
			return $query->fetchAll();
		}
	} // editar_usuario

	/**
	 * Apaga usuários
	 */
	public function del_user($parametros = array()){
		// O ID do usuário
		$user_id = null;

		// Verifica se existe o parâmetro "del" na URL
		if (chk_array($parametros, 0) == 'del') {
			// Configura o ID do usuário a ser apagado
			$user_id = chk_array($parametros, 1);
		}

		// Verifica se o ID não está vazio
		if (!empty($user_id)) {
			// O ID precisa ser inteiro
			$user_id = (int)$user_id;

			// Faz uma busca no banco de dados para trazer o nome da imagem salva no banco de dados
			$query = $this->db->query('SELECT imagem FROM `usuarios` WHERE id = ' . $user_id . ' ORDER BY id DESC');

			//Esse loop pega amenas o nome da imagem da busca anterior
			while ($row = $query->fetch()) {
				$imagem = $row['imagem'];
				//echo '<script type="text/javascript">alert("Resultado: '.$row['imagem'].'");</script>';
			}

			// Tenta fezer a exclusão da imagem
			try {
				//diretorio padrao onde está localizado a imagem
				$fileDIR = "views/_images/usuarios/" . $imagem;
				//echo '<script type="text/javascript">alert("Resultado: '.$fileDIR.'");</script>';

				//chama o unlink que serve para excluir a imagem no diretorio
				if (!empty($imagem)) {
					$this->excluirArquivo($fileDIR);
					//unlink( $fileDIR );
				}

				// Exclui do banco de dados todos os usuarios que tem permissao para poder substituir pelas novas
				$excluirPermissao = $this->db->delete('usuariosdownloads', 'usuarios_id', $user_id);

				// Deleta o usuário
				$query = $this->db->delete('usuarios', 'id', $user_id);

				if (!$query) {
					echo '<script type="text/javascript">alert("Erro ao excluir a imagem!");</script>';
				}
			} catch (Exception $e) {
				echo '<script type="text/javascript">alert("Erro ao Excluir a imagem: ' . $e->getMessage() . '");</script>';
			}

			// Redireciona para a página de registros
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . 'usuario/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . 'usuario/";</script>';
			return;
		}
	} // Função utilizada para Deletar usuarios

}// class usuarioModel
