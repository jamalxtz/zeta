<?php if ( ! defined('ABSPATH')) exit; ?>

<main class="bg-color">
  <div class="container-fluid">

    <h1 class="mt-4">Editar Usuário</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>usuario">Usuários</a></li>
      <li class="breadcrumb-item active">Editar Usuário</li>
    </ol>

    <div class="row">
      <div class="col-xl-12">

        <!-- Painel Editar Usuário-->
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

            <?php foreach ($lista as $fetch_userdata): ?>

              <fieldset>
              <legend>Dados Pessoais</legend>
                <div class="form-row">
                 <input style="display:none" class="form-control form-control-sm" name="id" type="number" placeholder="" value="<?php echo $fetch_userdata['id'] ?>" required>

                  <div class="form-group col-12 text-right">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitch1" name="situacao" <?php echo ( $fetch_userdata['situacao'] == "Ativo" ? "checked" : "" );  ?>>
                      <label class="custom-control-label" for="customSwitch1">Situação</label>
                    </div>
                  </div>

                  <div class="form-group col-6">
                    <small><strong>CPF: *</strong></small>
                    <input class="form-control form-control-sm" name="cpf" type="text" placeholder="000.000.000-00" data-mask="000.000.000-00" data-mask-selectonfocus="true" value="<?php echo $fetch_userdata['cpf'] ?>" required>
                  </div>

                  <div class="form-group col-6">
                    <small><strong>RG:</strong></small>
                    <input class="form-control form-control-sm" name="rg" type="text" placeholder="" value="<?php echo $fetch_userdata['rg'] ?>">
                  </div>

                  <div class="form-group col-4">
                    <small><strong>Nome: *</strong></small>
                    <input class="form-control form-control-sm" name="nome" type="text" placeholder="" value="<?php echo $fetch_userdata['nome'] ?>" required>
                  </div>

                  <div class="form-group col-8">
                    <small><strong>Sobrenome:</strong></small>
                    <input class="form-control form-control-sm" name="sobrenome" type="text" placeholder="" value="<?php echo $fetch_userdata['sobrenome'] ?>">
                  </div>

                  <div class="form-group col-12">
                    <small><strong>Email: *</strong></small>
                    <input class="form-control form-control-sm" name="email" type="email" placeholder="exemplo@email.com" value="<?php echo $fetch_userdata['email'] ?>" required>
                  </div>

                  <div class="form-group col-6">
                    <small><strong>Celular:</strong></small>
                    <input class="form-control form-control-sm" name="celular" type="text" placeholder="(62) 0 0000-0000" data-mask="(00) 0 0000-0000" data-mask-selectonfocus="true" value="<?php echo $fetch_userdata['celular'] ?>">
                  </div>

                  <div class="form-group col-6">
                    <small><strong>Telefone:</strong></small>
                    <input class="form-control form-control-sm" name="telefone" type="text" placeholder="(62) 0000-0000" data-mask="(00) 0000-0000" data-mask-selectonfocus="true" value="<?php echo $fetch_userdata['telefone'] ?>">
                  </div>

                </div>
              </fieldset>

              <fieldset>
              <legend>Endereço</legend>
                <div class="form-row">
                  <div class="form-group col-md-5">
                    <small><strong>CEP:</strong></small>
                    <div class="input-group mb-3">  
                      <input class="form-control form-control-sm" id="cep" name="cep" type="text" placeholder="00000-000" data-mask="00000-000" data-mask-selectonfocus="true" value="<?php echo $fetch_userdata['cep'] ?>">                                             
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-info" onclick="buscarCep()" type="button"><i id="cepIcone" class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-7">
                    <small><strong>Logradouro:</strong></small>
                    <input class="form-control form-control-sm" id="logradouro" name="logradouro" type="text" placeholder="" value="<?php echo $fetch_userdata['logradouro'] ?>">
                  </div>

                  <div class="form-group col-12">
                    <small><strong>Complemento:</strong></small>
                    <input class="form-control form-control-sm" id="complemento" name="complemento" type="text" placeholder="" value="<?php echo $fetch_userdata['complemento'] ?>">
                  </div>


                  <div class="form-group col-4">
                    <small><strong>Bairro:</strong></small>
                    <input class="form-control form-control-sm" id="bairro" name="bairro" type="text" placeholder="" value="<?php echo $fetch_userdata['bairro'] ?>">
                  </div>

                  <div class="form-group col-4">
                    <small><strong>Cidade:</strong></small>
                    <input class="form-control form-control-sm" id="cidade" name="cidade" type="text" placeholder="Goiânia" value="<?php echo $fetch_userdata['cidade'] ?>">
                  </div>

                  <div class="form-group col-4">
                    <small><strong>Estado:</strong></small>
                    <input class="form-control form-control-sm" id="estado" name="estado" type="text" placeholder="GO" value="<?php echo $fetch_userdata['estado'] ?>">
                  </div>
                </div>
              </fieldset>

              <fieldset>
              <legend>Permissões</legend>
                <div class="form-row">
                  <div class="form-group col-12">
                    <small><strong>Permissões: *</strong></small>
                    <div class="form-group col-lg-06">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="pAcessarAreaRestrita" value="AcessarAreaRestrita" <?php if(strpos($fetch_userdata['user_permissions'], 'AreaRestrita') !== false){ echo "checked";}?>>
                        <!-- A função Strpos verifica se a palavra do segundo pamarametro existe dentro do primeiro, utilizei para verificar se existe na lista de permissoes-->
                        <label class="custom-control-label" for="customCheck1">Acessar Área Restrita</label>
                      </div>
                    </div>
                    <div class="form-group col-lg-06">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck2" name="pAcessarSiteAdmin" value="AcessarSiteAdmin" <?php if(strpos($fetch_userdata['user_permissions'], 'AcessarSiteAdmin') !== false){ echo "checked";}?>>
                        <label class="custom-control-label" for="customCheck2">Acessar Site Admin</label>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
              <legend>Imagem</legend>
                <div class="row justify-content-center" style="padding-top: 20px">
                  <div class="col text-center">
                     <img src='<?php if($fetch_userdata["imagem"] != ""){
                        echo HOME_URI."views/_images/usuarios/".$fetch_userdata["imagem"];
                      }else{
                        echo HOME_URI."views/_images/sem-img.jpg";
                      }
                      ?>' id="img-preview" alt="..." class="mx-auto d-block img-thumbnail rounded media">

                     <div class="p-3 row">
                      <div class="form-group">
                        <input type="file" class="form-control-file" id="imagemCliente" name="imagemCliente">
                      </div>
                      <br>
                     </div>
                  </div>
                </div>
              </fieldset>
            <?php endforeach;?>

          <!-- FIM do corpo de modal cadastro de cliente-->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary">Limpar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
          
          </form>

        </div>
        <!-- Fim do Painel Editar Usuário-->

      </div>
    </div>

  </div>
</main>