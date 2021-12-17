<?php if ( ! defined('ABSPATH')) exit; 

  if(empty($_SESSION['CUcpf'])){
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
  }
?>

<?php
  if ( $this->logged_in ) {
    echo '<meta http-equiv="Refresh" content="0; url=' .HOME_URI. '">';
    echo '<script type="text/javascript">window.location.href = "' .HOME_URI. '";</script>';
  }
?>

<div id="layoutAuthentication">   
  <div id="layoutAuthentication_content" class="bg-imagem" id="bgimagem">

    <main>
      <div class="container mb-5">
        <div class="row justify-content-center">
          <div class="col-lg-10">

            <!--Painel de Registro-->
            <div class="card shadow-lg border-0 rounded-lg mt-5">
              <div class="card-header">
                <h3 class="text-center font-weight-light my-4"> <span class="fz-30 icon-diamond texto-azul" aria-hidden="true"></span>  Registro SiteAdmin</h3>
              </div>
              <div class="card-body">
              <!--Inicio do corpo do painel-->
                <input class="form-control py-4 hidden" name="url" id="url" type="text" value="<?php echo HOME_URI;?>views/_images/" />
                <input class="form-control py-4 hidden" name="url" id="pagina" type="text" value="login">

                <div class="row">
                  <div class="col-10">
                    <?php
                      // Carrega todos os métodos do modelo
                      $modelo->validate_register_form();
                      $lista = $modelo->editar_usuario( $parametros );
                      $listaDepartamentos = $modelo->pegar_lista_departamentos();
                      // Mensagem de feedback para o usuário
                      echo $modelo->form_msg;
                    ?>
                  </div>
                  <div class="text-right col-2">
                    <a type="button" href="<?php echo HOME_URI?>login" class="btn btn-dark btn-lg"> <i class="fas fa-reply mr-1"></i></a>
                  </div>
                </div>
                                        
                <!--Para que o Envio de imagens dÊ certo, é muito importante que tenha o enctype="multipart/form-data"  no form-->
                <form enctype="multipart/form-data" method="post" action="">

                <fieldset>
                <legend>Dados Pessoais</legend>
                  <div class="form-row">
                    <div class="form-group col-12 text-right hidden">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" name="situacao">
                        <label class="custom-control-label" for="customSwitch1">Situação</label>
                      </div>
                    </div>

                    <div class="form-group col-6">
                      <small><strong>CPF: *</strong></small>
                      <input class="form-control form-control-sm" name="cpf" type="text" placeholder="000.000.000-00" data-mask="000.000.000-00" data-mask-selectonfocus="true" value="<?php print_r($_SESSION['CUcpf']);?>">
                    </div>

                    <div class="form-group col-6">
                      <small><strong>RG:</strong></small>
                      <input class="form-control form-control-sm" name="rg" type="text" placeholder="" value="<?php print_r($_SESSION['CUrg']);?>">
                    </div>

                    <div class="form-group col-4">
                      <small><strong>Nome: *</strong></small>
                      <input class="form-control form-control-sm" name="nome" type="text" placeholder="" value="<?php print_r($_SESSION['CUnome']);?>" required>
                    </div>

                    <div class="form-group col-8">
                      <small><strong>Sobrenome:</strong></small>
                      <input class="form-control form-control-sm" name="sobrenome" type="text" value="<?php print_r($_SESSION['CUsobrenome']);?>" placeholder="">
                    </div>

                    <div class="form-group col-12">
                      <small><strong>Email: *</strong></small>
                      <input class="form-control form-control-sm" name="email" type="email" placeholder="exemplo@email.com" value="<?php print_r($_SESSION['CUemail']);?>" required>
                    </div>

                    <div class="form-group col-6">
                      <small><strong>Celular:</strong></small>
                      <input class="form-control form-control-sm" name="celular" type="text" placeholder="(62) 0 0000-0000" data-mask="(00) 0 0000-0000" data-mask-selectonfocus="true" value="<?php print_r($_SESSION['CUcelular']);?>">
                    </div>

                    <div class="form-group col-6">
                      <small><strong>Telefone:</strong></small>
                      <input class="form-control form-control-sm" name="telefone" type="text" placeholder="(62) 0000-0000" data-mask="(00) 0000-0000" data-mask-selectonfocus="true" value="<?php print_r($_SESSION['CUtelefone']);?>">
                    </div>
                  
                    <div class="form-group col-6">
                    <small class="col-form-label col-form-label-sm" for=""><strong>Departamento:</strong></small>
                    <select id="select-servicos" name="departamento" class="form-control form-control-sm" required>
                      <option selected></option>
                            <?php foreach ($listaDepartamentos as $fetch_userdata): ?>
                              <option value="<?php echo $fetch_userdata['id'] ?>"><?php echo $fetch_userdata['descricao'] ?></option>
                            <?php endforeach;?>
                    </select>
                  </div>
                </div>
                </fieldset>

                <fieldset>
                <legend>Endereço</legend>
                  <div class="form-row">
                    <div class="form-group col-md-5">
                      <small><strong>CEP:</strong></small>
                      <div class="input-group mb-3">  
                        <input class="form-control form-control-sm" id="cep" name="cep" type="text" placeholder="00000-000" data-mask="00000-000" data-mask-selectonfocus="true" value="<?php print_r($_SESSION['CUcep']);?>">
                        <div class="input-group-append">
                          <button class="btn btn-sm btn-info" onclick="buscarCep()" type="button"><i id="cepIcone" class="fas fa-search"></i></button>
                        </div>
                      </div>
                    </div>

                    <div class="form-group col-md-7">
                      <small><strong>Logradouro:</strong></small>
                      <input class="form-control form-control-sm" id="logradouro" name="logradouro" type="text" placeholder="" value="<?php print_r($_SESSION['CUlogradouro']);?>">
                    </div>

                    <div class="form-group col-12">
                      <small><strong>Complemento:</strong></small>
                      <input class="form-control form-control-sm" id="complemento" name="complemento" type="text" placeholder="" value="<?php print_r($_SESSION['CUcomplemento']);?>">
                    </div>

                    <div class="form-group col-4">
                      <small><strong>Bairro:</strong></small>
                      <input class="form-control form-control-sm" id="bairro" name="bairro" type="text" placeholder="" value="<?php print_r($_SESSION['CUbairro']);?>">
                    </div>

                    <div class="form-group col-4">
                      <small><strong>Cidade:</strong></small>
                      <input class="form-control form-control-sm" id="cidade" name="cidade" type="text" placeholder="Goiânia" value="<?php print_r($_SESSION['CUcidade']);?>">
                    </div>

                    <div class="form-group col-4">
                      <small><strong>Estado:</strong></small>
                      <input class="form-control form-control-sm" id="estado" name="estado" type="text" placeholder="GO" value="<?php print_r($_SESSION['CUestado']);?>">
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                <legend>Senha</legend>
                  <div class="form-row">
                    <div class="form-group col-6">
                      <small><strong>Senha: *</strong></small>
                      <input class="form-control form-control-sm" name="senha" type="password" placeholder="" value="<?php print_r($_SESSION['CUsenha']);?>" required>
                    </div>

                    <div class="form-group col-6">
                      <small><strong>Confirmar: *</strong></small>
                      <input class="form-control form-control-sm" name="confirmar" type="password" placeholder="" value="<?php print_r($_SESSION['CUconfirma']);?>" required>
                    </div>

                    <div class="form-group col-12">
                      <small><strong>Dica:</strong></small>
                      <input class="form-control form-control-sm" name="dica" type="text" placeholder="" value="<?php print_r($_SESSION['CUdica']);?>">
                    </div>
                  </div>
                </fieldset>


                <fieldset>
                <legend>Imagem</legend>
                  <div class="row justify-content-center" style="padding-top: 20px">
                    <div class="col text-center">
                      <img src="<?php echo HOME_URI;?>views/_images/sem-img.jpg" id="img-preview" alt="..." class="mx-auto d-block img-thumbnail rounded media">
                      <div class="p-3 row">
                        <div class="form-group">
                          <input type="file" class="form-control-file" id="imagemCliente" name="imagemCliente">
                        </div>
                        <br>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <div class="text-right">
                  <button type="button" class="btn btn-secondary">Limpar</button>
                  <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
                                                                            
                </form>
              <!--Fim do corpo do painel-->
              </div>

              <div class="card-footer text-center">
                  <div class="small"><a href="<?php echo HOME_URI."/login"?>">Voltar ao login</a></div>
              </div>

            </div>
            <!--FIM doPainel de Registro-->

          </div>
        </div>
      </div>
    </main>

  </div>
</div>