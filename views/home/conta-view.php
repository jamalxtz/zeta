<?php if ( ! defined('ABSPATH')) exit; ?>

<main class='bg-color'>
  <div class="container-fluid">
      
    <h1 class="mt-4">Minha Conta</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item active">Minha Conta</li>
    </ol>

    <div class="row">
    </div>

    <!--Card do Logotipo-->
    <div class="card shadow mb-4">
      <div class="card-header">
        <span class="icon-user mr-1" aria-hidden="true"></span>Meu Cadastro
      </div>
      
      <div class="card-body">
      <!--Inicio do corpo do painel-->
        <input type="text" class="hidden" id="urlPadrao" name="url-edit" value="<?php echo HOME_URI?>">

        <?php
          //print_r($_SESSION["userdata"]["id"]);
          // Carrega todos os métodos do modelo
          $modeloMinhaConta->alterar_senha();
          $lista = $modeloMinhaConta->verDadosMinhaConta($_SESSION["userdata"]["id"]);
          // Mensagem de feedback para o usuário
          echo $modeloMinhaConta->form_msg;
        ?>

        <form enctype="multipart/form-data" method="post" action="">

        <?php foreach ($lista as $fetch_userdata): ?>
        <div class="row">
          <div class="col-md-4 text-center">
            <img src='<?php if($fetch_userdata["imagem"] != ""){
                    echo HOME_URI."views/_images/usuarios/".$fetch_userdata["imagem"];
                  }else{
                    echo HOME_URI."views/_images/sem-img.jpg";
                  }
                  ?>' id="img-preview" alt="..." class="mx-auto d-block img-thumbnail rounded">
          </div>
          <div class="col-md-8">
            <h4>
              <?php echo $fetch_userdata['nome'] ?>
            </h4>
            <p>
              <?php 
                if($fetch_userdata['situacao'] == 'Ativo'){
                  echo '<span class="badge badge-info">Ativo</span>';
                }else{
                  echo '<span class="badge badge-secondary">Inativo</span>';
                }
              ?>
            </p>
            <small>
              <cite title="San Francisco, USA"><?php echo $fetch_userdata['cidade'] ?>, <?php echo $fetch_userdata['estado'] ?> <span class="icon-room mr-1" aria-hidden="true"></span>
              </cite>
            </small>
            <p>
              <span class="icon-envelope mr-1" aria-hidden="true"></span><?php echo $fetch_userdata['email'] ?>
              <br />
              <span class="icon-phone mr-1" aria-hidden="true"></span><?php echo $fetch_userdata['telefone'] ?> / <?php echo $fetch_userdata['celular'] ?>
              <br />
              <div class="mb-4 mt-4">
                <a href="<?php echo HOME_URI?>usuario/visualizar/edit/<?php echo $fetch_userdata['id'] ?>" type="button" class="btn btn-primary btn-sm"><span class="icon-user mr-1" aria-hidden="true"></span> Ver Detalhes</a>
                <button type="button" class="btn btn-secondary btn-sm" role="button" data-toggle="modal" data-target="#modal-cadastrar"><span class="icon-lock mr-1" aria-hidden="true"></span> Alterar Senha</button>
              </div>
                
            </p>
          </div>
        </div>
      <!-- FIM Inicio do corpo do painel-->
      </div>
      <div class="card-footer small text-muted">Cadastrado desde: <?php echo date("d/m/Y", strtotime($fetch_userdata['cadastro']));  ?></div>
      <?php endforeach;?>
    </div>
    <!--Fim do card do Meu Cadastro-->

  </div>
</main>

<!--Modal Editar Senha -->
<div class="modal fade" id='modal-cadastrar' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><span class="icon-lock mr-1" aria-hidden="true"></span> Alterar Senha</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
      <!--Corpo do modal-->
        <form enctype="multipart/form-data" method="post" action="">
        
        <small class="mt-4"><strong>Senha Antiga:</strong></small>
        <input type="text" class="form-control" id="senhaAntiga" name="senhaAntiga">

        <small class="mt-4"><strong>Nova Senha:</strong></small>
        <input type="text" class="form-control" id="novaSenha" name="novaSenha">
        <small class="mt-4"><strong>Confirmar Senha:</strong></small>
        <input type="text" class="form-control" id="confirmacao" name="confirmacao">
      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal")">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--Fim Modal Editar Senha -->