<?php if ( ! defined('ABSPATH')) exit; ?>

      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Bruno MSS 2020</div>
            <div>
              <a href="<?php echo HOME_URI;?>home/ajuda">Ajuda</a>
                &middot;
              <a href="" role="button" data-toggle="modal" data-target="#modal-versao">Licença &amp; Versão</a>
            </div>
          </div>
        </div>
      </footer>
    <!--Fechamento da tag id="layoutSidenav_content" aberta no header, essa tag define o escopo da página-->
    </div>

    <!--Modal Licença e Versão-->
    <div class="modal fade" id='modal-versao' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <p class="modal-title" id="myModalLabel">Licença &amp; Versão</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <!--Corpo do modal-->
            <div class="row">
              <div class="col-sm-12">
                <small class="mt-4"><strong>Desenvolvido por:</strong></small>
                <small class="mt-4">Bruno Mateus S. Souza</small>
              </div>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Telefone:</strong></small>
                <small class="mt-4">(62) 9 9462-6462</small>
              </div>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Email:</strong></small>
                <small class="mt-4">bruno_mss@outlook.com</small>
              </div>
              <hr>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Versão do Siteadmin:</strong></small>
                <small class="mt-4">2.2</small>
              </div>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Versão do site:</strong></small>
                <small class="mt-4">1.2</small>
              </div>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Versão do banco de dados:</strong></small>
                <small class="mt-4">1.3</small>
              </div>
              <div class="col-sm-12">
                <small class="mt-4"><strong>Data última atualização</strong></small>
                <small class="mt-4">18/01/2021</small>
              </div>
              <small class="mt-4"><strong>Últimas atualizações:</strong></small>
              <textarea type="text" rows="5" class="form-control form-control-sm" id="descricao" name="descricao"></textarea>
            </div>
          <!--FIM Corpo do modal-->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>    
          </div>

          </form>

        </div>
      </div>
    </div>
    <!--FIM Modal Licença e Versão-->

    <!--Modal Reportar Erro-->
    <div class="modal fade" id='modal-erro' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <p class="modal-title" id="myModalLabel">Reportar um Erro</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <!--Corpo do modal-->
            <form class="form-area " id="enviar-contato-id" action="" method="post" class="contact-form text-right">

            <div class="alert alert-info alert-dismissible fade show" role="alert">
              <strong>Atenção</strong> Escreva de forma detalhada o erro identificado, não se esqueça de mencionar qual o local exato e em qual situação o erro ocorreu.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <input name="url" id="url" class="common-input mb-20 form-control hidden" type="text" value="<?php echo HOME_URI ?>">

            <div class="row">
              <div class="col-sm-12">
                <small class="mt-4"><strong>Detalhes:</strong></small>
                <textarea type="text" rows="10" class="form-control form-control-sm" id="erroDetalhes" name="erroDetalhes" required></textarea>
                </div>
            </div>
          <!--FIM Corpo do modal-->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>    
            <button type="button" id="btnEnviarErro" onclick="ReportarErro()" class="btn btn-success">Enviar</button> 
          </div>

          </form>

        </div>
      </div>
    </div>
    <!--FIM Modal Reportar Erro-->

    <!-- Scrip Jquery e Bootstrap-->
    <script src="<?php echo HOME_URI;?>views/_js/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script src="<?php echo HOME_URI;?>views/_js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Script Principal -->
    <script src="<?php echo HOME_URI;?>views/_js/scripts.js"></script>
    <!-- Script Graficos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo HOME_URI;?>views/_js/chart/chart-area-demo.js"></script>
    <script src="<?php echo HOME_URI;?>views/_js/chart/chart-bar-demo.js"></script>
    <!-- Script Tabelas -->
    <script src="<?php echo HOME_URI;?>views/_js/dataTables.min.js"></script>
    <script src="<?php echo HOME_URI;?>views/_js/dataTables-custom.js"></script>

    <script src="<?php echo HOME_URI;?>views/_js/imgpreview.js"></script>
    <!-- Mascara que limita o numero de caracteres e insere pontos traços,etc -->
    <script src="<?php echo HOME_URI;?>views/_js/jquery.mask.js"></script>
    <!-- Exibe notificações flutuantes -->
    <script src="<?php echo HOME_URI;?>views/_js/notify.js"></script>
    
    <!-- <script src="<?php echo HOME_URI;?>views/_js/atendimentos.js"></script> -->
    <!-- <script src="<?php echo HOME_URI;?>views/_js/inicio/menu.js"></script> -->
    <!-- <script src="<?php echo HOME_URI;?>views/_js/inicio/apresentacao.js"></script> -->
    <?php echo $this->elementosFooter[0] ?>
    
  </body>
</html>