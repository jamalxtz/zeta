<?php if ( ! defined('ABSPATH')) exit; 
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI?>models/finances/ajax-finances-model.php">
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="horta">

    <!-- Painel do Dashboard-->
    <div class="card shadow mb-4">

      <?php 
        // Lista os dados
        $lista_total_receitas = $modelo->listar_total_receitas( $parametros );
        $lista_total_despesas = $modelo->listar_total_despesas( $parametros );
        $lista_total_resultado = $modelo->listar_total_resultado( $parametros );
        // Função que Seleciona o mês que irá exibir os dados
        // Primeiro verifica o mês anterior, se não houver nenhuma conta a pagar ou a receber pendente então exibe o mês atual
        $link_mes_atual = $modelo->selecionar_mes() ;
      ?>
      
      <div class="card-body">
      <!-- Corpo do painel -->
        <div class="text-right">
            <a type="button" href="<?php echo HOME_URI?>cliente/novo" class="btn btn-dark btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Mês Anterior"><i class="fas fa-arrow-left mr-1"></i></a>
            <a type="button" href="<?php echo HOME_URI?>cliente/novo" class="btn btn-dark btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Abrir Calendario">Janeiro, 2021</i></a>
            <a type="button" href="<?php echo HOME_URI?>cliente/novo" class="btn btn-dark btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Próximo Mês"><i class="fas fa-arrow-right mr-1"></i></a>
        </div>
        <p class="form_success"></p>
        <br>

          <!--Atalhos -->
          <div class="row">

            <div class="col-xl-3 col-md-6">
              <div class="card bg-success text-white mb-4">
                <div class="card-body" style="transform: rotate(0);">
                  <div class="row">
                    <div class="col-6">Receitas</div>
                    <div class="col-6 fz-30 text-white text-right"><i class="fas fa-hand-holding-usd"></i></div>
                  </div>
                  <!--stretched-link funciona apenas no body do painel por causa do style transform-->
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>finances/receitas/<?php echo $link_mes_atual?>"></a>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                  <?php foreach ($lista_total_receitas as $fetch_userdata): ?>
                    <small class="small text-white" data-toggle="tooltip" data-placement="top" title="Total Pendente">R$ 
                      <?php
                        echo $modelo->formatar_valor($fetch_userdata['TotalPendente']);
                      ?>
                    </small>
                    <small>
                      <strong data-toggle="tooltip" data-placement="top" title="Total Quitado">R$ 
                        <?php 
                          echo $modelo->formatar_valor($fetch_userdata['TotalQuitado']);
                        ?>
                      </strong>
                    </small>
                  <?php endforeach;?>
                </div>

              </div>
            </div>

            <div class="col-xl-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                <div class="card-body"  style="transform: rotate(0);">
                  <div class="row">
                    <div class="col-6">Despesas</div>
                    <div class="col-6 fz-30 text-white text-right"><i class="fas fa-credit-card"></i></div>
                  </div>
                  <!--stretched-link funciona apenas no body do painel por causa do style transform-->
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>finances/despesas/<?php echo $link_mes_atual?>"></a>
                </div>
                
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <?php foreach ($lista_total_despesas as $fetch_userdata): ?>
                    <small class="small text-white" data-toggle="tooltip" data-placement="top" title="Total Pendente">R$ 
                      <?php
                        echo $modelo->formatar_valor($fetch_userdata['TotalPendente']);
                      ?>
                    </small>
                    <small>
                      <strong data-toggle="tooltip" data-placement="top" title="Total Quitado">R$ 
                        <?php 
                          echo $modelo->formatar_valor($fetch_userdata['TotalQuitado']);
                        ?>
                      </strong>
                    </small>
                  <?php endforeach;?>
                </div>

              </div>
            </div>

            <div class="col-xl-3 col-md-6">
              <div class="card bg-info text-white mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-6"><nobr>Investimentos</nobr></div>
                    <div class="col-6 fz-30 text-white text-right"><i class="fas fa-piggy-bank"></i></div>
                  </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <small class="small text-white stretched-link">R$ 0,00</small>
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>finances/investimentos"><strong>R$ 0,00</strong></a>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6">
              <div class="card bg-dark mb-4">
                <div class="card-body text-white">
                  <div class="row">
                    <div class="col-6">Resultado</div>
                    <div class="col-6 fz-30 text-right"><i class="fas fa-wallet"></i></div>
                  </div>
                </div>
    
                <div class="card-footer d-flex align-items-center justify-content-between text-white">
                  <?php foreach ($lista_total_resultado as $fetch_userdata): ?>
                    <small class="small" data-toggle="tooltip" data-placement="top" title="Resultado Pendente">R$ 
                      <?php
                        echo $modelo->formatar_valor($fetch_userdata['ResultadoPendente']);
                      ?>
                    </small>
                    <small>
                      <strong data-toggle="tooltip" data-placement="top" title="Resultado Quitado">R$ 
                        <?php 
                          echo $modelo->formatar_valor($fetch_userdata['ResultadoQuitado']);
                        ?>
                      </strong>
                    </small>
                  <?php endforeach;?>
                </div>

              </div>
            </div>

          </div>
          <!--Fim dos Atalhos -->

          <!--Gráficos -->
          <div class="row">
            <div class="col-xl-6">
              <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-area mr-1"></i>Receitas</div>
                <div class="card-body"><canvas id="graficoReceitas" width="100%" height="60"></canvas></div>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Despesas</div>
                <div class="card-body"><canvas id="graficoDespesas" width="100%" height="60"></canvas></div>
              </div>
            </div>
          </div>
          <!--Fim dos Gráficos -->
                  
        
        
      <!-- FIM Corpo do painel -->
      </div>
      <div class="card-footer">
        <div class="text-right ">
          <button type="submit" class="btn btn-dark shadow">Fechar o Mês</button>
        </div>
      </div>
              
    </div>
    <!-- Fim do Painel do Dashboard-->

  </div>
</main>


<!--Modal Dados do Arduino-->
<div class="modal fade" id='idModalDadosArduino' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Dados do Arduino</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
      <!--Corpo do modal-->

        <div class="form-group row">
          <strong><label for="adnEquipamento" class="col-6 col-form-label">Equipamento:</label></strong>
          <div class="col-6">
            <label id="adnEquipamento" class="col-form-label">...</label>
          </div>
        </div>

        <div class="form-group row">
          <strong><label for="adnStatus" class="col-6 col-form-label"><nobr>Status:</nobr></label></strong>
          <div class="col-6">
            <label id="adnStatus" class="col-form-label"><span id="status" class="badge"></sp</label>
          </div>
        </div>

        <div class="form-group row">
          <strong><label for="adnUltimaRequisicao" class="col-6 col-form-label"><nobr>Ultima Requisição:</nobr></label></strong>
          <div class="col-6">
            <label id="adnUltimaRequisicao" class="col-form-label">...</label>
          </div>
        </div>

        <div class="form-group row">
          <strong><label for="adnIPLocal" class="col-6 col-form-label"><nobr>IP Local:</nobr></label></strong>
          <div class="col-6">
            <label id="adnIPLocal" class="col-form-label">...</label>
          </div>
        </div>

        <div class="form-group row">
          <strong><label for="adnIPExterno" class="col-6 col-form-label"><nobr>IP Externo:</nobr></label></strong>
          <div class="col-6">
            <label id="adnIPExterno" class="col-form-label">...</label>
          </div>
        </div>

        <div class="form-group row">
          <strong><label for="adnMAC" class="col-6 col-form-label">MAC:</label></strong>
          <div class="col-6">
            <label id="adnMAC" class="col-form-label">...</label>
          </div>
        </div>

      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal")">Fechar</button>  
      </div>

    </div>
  </div>
</div>
<!--Fim Modal  Dados do Arduino -->