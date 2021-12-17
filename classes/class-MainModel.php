<?php
/**
 * MainModel - Modelo geral (Classe com as funções globais, comuns a todos os métodos)
 *
 * 
 *
 * @package TutsupMVC
 * @since 0.1
 */
class MainModel{
	/**
	 * $form_data
	 *
	 * Os dados de formulários de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg
	 *
	 * As mensagens de feedback para formulários.
	 *
	 * @access public
	 */	
	public $form_msg;

	/**
	 * $form_confirma
	 *
	 * Mensagem de confirmação para apagar dados de formulários
	 *
	 * @access public
	 */
	public $form_confirma;

	/**
	 * $db
	 *
	 * O objeto da nossa conexão PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * $controller
	 *
	 * O controller que gerou esse modelo
	 *
	 * @access public
	 */
	public $controller;

	/**
	 * $parametros
	 *
	 * Parâmetros da URL
	 *
	 * @access public
	 */
	public $parametros;

	/**
	 * $userdata
	 *
	 * Dados do usuário
	 *
	 * @access public
	 */
	public $userdata;
	
	/**
	 * Inverte datas 
	 *
	 * Obtém a data e inverte seu valor.
	 * De: d-m-Y H:i:s para Y-m-d H:i:s ou vice-versa.
	 *
	 * @since 0.1
	 * @access public
	 * @param string $data A data
	 */
	public function inverte_data( $data = null ) {
		// Configura uma variável para receber a nova data
		$nova_data = null;
		// Se a data for enviada
		if ( $data ) {
			// Explode a data por -, /, : ou espaço
			$data = preg_split('/\-|\/|\s|:/', $data);
			// Remove os espaços do começo e do fim dos valores
			$data = array_map( 'trim', $data );
			// Cria a data invertida
			$nova_data .= chk_array( $data, 2 ) . '-';
			$nova_data .= chk_array( $data, 1 ) . '-';
			$nova_data .= chk_array( $data, 0 );
			// Configura a hora
			if ( chk_array( $data, 3 ) ) {
				$nova_data .= ' ' . chk_array( $data, 3 );
			}
			// Configura os minutos
			if ( chk_array( $data, 4 ) ) {
				$nova_data .= ':' . chk_array( $data, 4 );
			}
			// Configura os segundos
			if ( chk_array( $data, 5 ) ) {
				$nova_data .= ':' . chk_array( $data, 5 );
			}
		}
		// Retorna a nova data
		return $nova_data;
	} // inverte_data

	/**
	 * Salva a Imagem na pasta
	 */
	public function upload_imagem(){

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

	/**
	 * Remove a imagem da pasta
	 */
	public function excluirArquivo($arquivo){
		if (file_exists($arquivo))
			unlink($arquivo);
		return $arquivo;
	}

} // MainModel