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

<main class="bg-color">
  <div class="container-fluid">

    <h1 class="mt-4">Novo Usuário</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>usuario">Usuários</a></li>
      <li class="breadcrumb-item active">Novo Usuário</li>
    </ol>

    <div class="row">
      <div class="col-xl-12">

        <!-- Painel Novo Usuário-->
        <div class="card shadow mb-4">
                  
          <div class="card-body">
          <!-- Corpo do painel -->

            <div class="row mb-4">
              <div class="text-right col-12">
                <a type="button" href="<?php echo HOME_URI?>usuario" class="btn btn-dark btn-lg" data-toggle="tooltip" data-placement="top" title="Voltar"> <i class="fas fa-reply mr-1"></i></a>
              </div>
            </div>

            <?php
              // Carrega todos os métodos do modelo
              $modelo->cadastrar_usuario();
              $lista = $modelo->editar_usuario( $parametros );
              // Mensagem de feedback para o usuário
               echo $modelo->form_msg;
            ?>
                    
            <!--Para que o Envio de imagens dÊ certo, é muito importante que tenha o enctype="multipart/form-data"  no form-->
            <form enctype="multipart/form-data" method="post" action="">

            <fieldset>
            <legend>Dados Pessoais</legend>
              <div class="form-row">
                <div class="form-group col-12 text-right">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="situacao" checked>
                    <label class="custom-control-label" for="customSwitch1">Situação</label>
                  </div>
                </div>

                <div class="form-group col-6">
                  <small><strong>CPF: *</strong></small>
                  <input class="form-control form-control-sm" name="cpf" type="text" placeholder="000.000.000-00" data-mask="000.000.000-00" data-mask-selectonfocus="true" value="<?php print_r($_SESSION['CUcpf']);?>" required>
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

          <!-- FIM do corpo do painel-->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary">Limpar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>

          </form>

        </div>
        <!-- Fim do Panel Novo Usuário-->

      </div>
    </div>

  </div>
</main>