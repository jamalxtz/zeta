<?php if ( ! defined('ABSPATH')) exit; 
  //Se o usuario tiver logado o comando abaixo redireciona para a pagina principal do siteAdmin
  if ( $this->logged_in ) {
    echo '<meta http-equiv="Refresh" content="0; url=' .HOME_URI. '">';
    echo '<script type="text/javascript">window.location.href = "' .HOME_URI. '";</script>';
  }
?>

<div id="layoutAuthentication">
<!--<div id="layoutAuthentication_content" class="bg-imagem" id="bg-imagem" style="background-image: url(<?php echo HOME_URI;?>views/_images/rodape_1782040144.jpg);"> -->
  <div id="layoutAuthentication_content" class="bg-imagem" id="bgimagem">

    <main>
      <div class="container mb-5">
        <div class="row justify-content-center">
          <div class="col-lg-5">

            <!--Painel de Login-->
            <div class="card shadow-lg border-0 rounded-lg mt-5">

              <div class="card-header">
                <h3 class="text-center font-weight-light my-4"> <span class="fz-30 icon-diamond texto-azul" aria-hidden="true"></span> Zeta Finances</h3>
              </div>

              <div class="card-body">
              <!--Corpo do painel-->
                <form method="post">
                  <input class="form-control py-4 hidden" name="url" id="url" type="text" value="<?php echo HOME_URI;?>views/_images/" />
                  <input class="form-control py-4 hidden" name="url" id="pagina" type="text" value="login">
                  <?php
                    if ( $this->login_error ) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $this->login_error . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            </div>'; 
                    }
                  ?>
                  <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Email</label><input class="form-control py-4" name="userdata[user]" type="text" placeholder="Informe seu email" /></div>
                  <div class="form-group"><label class="small mb-1" for="inputPassword">Senha</label><input class="form-control py-4" name="userdata[user_password]" type="password" placeholder="Informe sua senha" /></div>
                  <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                        <label class="custom-control-label" for="rememberPasswordCheck">Lembrar senha</label>
                      </div>
                  </div>
                  <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                      <a class="small" href="">Esqueceu a senha?</a>
                      <input class="btn btn-primary" type="submit" value="Entrar">
                  </div>
                </form>
              <!--Fim do corpo do painel-->
              </div>

              <div class="card-footer text-center">
                  <div class="small"><a href="<?php echo HOME_URI."login/registro"?>">Precisa de uma conta?</a></div>
              </div>

            </div>
            <!--Fim do painel de login-->

          </div>
        </div>
      </div>
    </main>

  </div>
<!--Div id="layoutAuthentication_content" ao ser fechada causa erro no rodapé-->