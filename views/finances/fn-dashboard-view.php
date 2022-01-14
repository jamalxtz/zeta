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
    
    <!-- Hidden Inputs -->
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input class="hidden" type="text" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="dashboard">

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
        <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataReferenciaDS" name="txtDataReferenciaDS" value="">
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
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>receitas/<?php echo $link_mes_atual?>"></a>
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
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>despesas/"></a>
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
              <div class="charts" id="grafico-receitas"></div>
            </div>
            <div class="col-xl-6">
              <div class="charts" id="grafico-despesas"></div>
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
