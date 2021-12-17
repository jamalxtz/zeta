<?php if ( ! defined('ABSPATH')) exit; ?>

<main class="bg-color">
  <div class="container-fluid">

    <h1 class="mt-4">Visualizar Usuário</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>usuario">Usuários</a></li>
      <li class="breadcrumb-item active">Visualizar Usuário</li>
    </ol>

    <div class="row">
      <div class="col-xl-12">

        <!-- Painel de vizualização do Usuário-->
        <div class="card shadow mb-4">
                  
          <div class="card-body">
          <!-- Corpo do painel -->
            <?php
              // Carrega todos os métodos do modelo
              $modelo->cadastrar_usuario();
              $lista = $modelo->editar_usuario( $parametros ); 
            ?>

            <div class="row mb-4">
              <?php foreach ($lista as $fetch_userdata): ?>
              <div class="text-right col-12">
                <a type="button" href="<?php echo HOME_URI?>usuario/editar/edit/<?php echo $fetch_userdata['id'] ?>" class="btn btn-success btn-lg" data-toggle="tooltip" data-placement="top" title="Editar Usuário"> <i class="fas fa-pen mr-1"></i></a>
                <a type="button" href="<?php echo HOME_URI?>usuario" class="btn btn-dark btn-lg" data-toggle="tooltip" data-placement="top" title="Voltar"> <i class="fas fa-reply mr-1"></i></a>
              </div>
            </div>

            <form method="post" action="">     

            <fieldset>
            <legend>Dados Pessoais</legend>
              <div class="form-row">
                <input style="display:none" class="form-control form-control-sm" name="id" type="number" placeholder="" value="<?php echo $fetch_userdata['id'] ?>" readonly>
                <div class="form-group col-6">
                  <small><strong>CPF:</strong></small>
                  <input class="form-control form-control-sm" name="cpf" type="text" placeholder="" value="<?php echo $fetch_userdata['cpf'] ?>" readonly>
                </div>

                <div class="form-group col-6">
                  <small><strong>RG:</strong></small>
                  <input class="form-control form-control-sm" name="rg" type="text" placeholder="" value="<?php echo $fetch_userdata['rg'] ?>" readonly>
                </div>

                <div class="form-group col-4">
                  <small><strong>Nome:</strong></small>
                  <input class="form-control form-control-sm" name="nome" type="text" placeholder="" value="<?php echo $fetch_userdata['nome'] ?>" readonly>
                </div>

                <div class="form-group col-8">
                  <small><strong>Sobrenome:</strong></small>
                  <input class="form-control form-control-sm" name="sobrenome" type="text" placeholder="" value="<?php echo $fetch_userdata['sobrenome'] ?>" readonly>
                </div>

                <div class="form-group col-12">
                  <small><strong>Email:</strong></small>
                  <input class="form-control form-control-sm" name="email" type="email" placeholder="" value="<?php echo $fetch_userdata['email'] ?>" readonly>
                </div>

                <div class="form-group col-6">
                  <small><strong>Telefone:</strong></small>
                  <input class="form-control form-control-sm" name="telefone" type="text" placeholder="" value="<?php echo $fetch_userdata['telefone'] ?>" readonly>
                </div>

                <div class="form-group col-6">
                  <small><strong>Celular:</strong></small>
                  <input class="form-control form-control-sm" name="celular" type="text" placeholder="" value="<?php echo $fetch_userdata['celular'] ?>" readonly>
                </div>

              </div>
            </fieldset>

            <fieldset>
            <legend>Endereço</legend>
              <div class="form-row">
                <div class="form-group col-md-5">
                  <small><strong>CEP:</strong></small>
                  <div class="input-group mb-3">  
                    <input class="form-control form-control-sm" id="cep" name="cep" type="text" placeholder="Somente números" value="<?php echo $fetch_userdata['cep'] ?>" readonly>                                             
                    <div class="input-group-append">
                      <button class="btn btn-sm btn-info" type="button"><i id="cepIcone" class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>

                <div class="form-group col-md-7">
                  <small><strong>Logradouro:</strong></small>
                  <input class="form-control form-control-sm" name="logradouro" type="text" placeholder="" value="<?php echo $fetch_userdata['logradouro'] ?>" readonly>
                </div>

                <div class="form-group col-12">
                  <small><strong>Complemento:</strong></small>
                  <input class="form-control form-control-sm" name="complemento" type="text" placeholder="" value="<?php echo $fetch_userdata['complemento'] ?>" readonly>
                </div>

                <div class="form-group col-4">
                  <small><strong>Bairro:</strong></small>
                  <input class="form-control form-control-sm" name="bairro" type="text" placeholder="" value="<?php echo $fetch_userdata['bairro'] ?>" readonly>
                </div>

                <div class="form-group col-4">
                  <small><strong>Cidade:</strong></small>
                  <input class="form-control form-control-sm" name="cidade" type="text" placeholder="" value="<?php echo $fetch_userdata['cidade'] ?>" readonly>
                </div>

                <div class="form-group col-4">
                  <small><strong>Estado:</strong></small>
                  <input class="form-control form-control-sm" name="estado" type="text" placeholder="" value="<?php echo $fetch_userdata['estado'] ?>" readonly>
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
                  ?>' id="img-preview" alt="..." class="mx-auto d-block img-thumbnail rounded ">
                </div>
              </div>
            </fieldset>
            <?php endforeach;?>
          <!-- FIM do corpo de modal cadastro de cliente-->
          </div>

          <div class="modal-footer">
            <a href="<?php echo HOME_URI;?>usuario" class="btn btn-secondary">Voltar</a>
            <!--<button type="submit" class="btn btn-primary">Salvar</button>-->
          </div>
          
          </form>

        </div>
        <!-- Fim do Painel de vizualização do Usuário-->

      </div>
    </div>

  </div>
</main>