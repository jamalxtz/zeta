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
      <div class="card-header d-flex justify-content-between">
        <div><i class="fas fa-chart-area mr-1"></i>Análise Mensal</div>
        <div class="col-lg-3 col-md-4 col-6">
          <!--form-control-->
          <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataReferencia" name="txtDataReferencia" value="">
        </div>
      </div>
      <div class="card-body">
      <!-- Corpo do painel -->

          <!--Atalhos -->
          <div class="row">

            <!-- Receitas -->
            <div class="col-xl-4 col-md-6">
              <div class="card bg-success text-white mb-4">
                <div class="card-body" style="transform: rotate(0);">
                  <div class="row">
                    <div class="col-6">Receitas</div>
                    <div class="col-6 fz-30 text-white text-right"><i class="icone-dashboard fas fa-hand-holding-usd"></i></div>
                  </div>
                  <div class="row">
                    <h2 id="totalReceitasDS"></h2>
                  </div>
                  <!--stretched-link funciona apenas no body do painel por causa do style transform-->
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>receitas/"></a>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                  <small class="small text-white" data-toggle="tooltip" data-placement="top" title="Total Pendente"> 
                    <span id="totalPendenteReceitasDS"></span>
                  </small>
                  <small>
                    <strong data-toggle="tooltip" data-placement="top" title="Total Quitado"> 
                      <span id="totalQuitadoReceitasDS"></span>
                    </strong>
                  </small>
                </div>
              </div>
            </div>

            <!-- Despesas -->
            <div class="col-xl-4 col-md-6">
              <div class="card bg-danger text-white mb-4">
                <div class="card-body"  style="transform: rotate(0);">
                  <div class="row">
                    <div class="col-6">Despesas</div>
                    <div class="col-6 fz-30 text-white text-right"><i class="icone-dashboard fas fa-credit-card"></i></div>
                  </div>
                  <div class="row">
                    <h2 id="totalDespesasDS"></h2>
                  </div>
                  <!--stretched-link funciona apenas no body do painel por causa do style transform-->
                  <a class="small text-white stretched-link" href="<?php echo HOME_URI?>despesas/"></a>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <small class="small text-white" data-toggle="tooltip" data-placement="top" title="Total Pendente">
                    <span id="totalPendenteDespesasDS"></span>
                  </small>
                  <small>
                    <strong data-toggle="tooltip" data-placement="top" title="Total Quitado">
                      <span id="totalQuitadoDespesasDS"></span>
                    </strong>
                  </small>
                </div>
              </div>
            </div>

            <!-- Investimentos 
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
            </div>-->

            <!-- Resultado -->
            <div class="col-xl-4 col-md-6">
              <div class="card bg-light mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">Lucro/Prejuízo</div>
                    <div class="col-6 fz-30 text-right"><i class="icone-dashboard fas fa-wallet"></i></div>
                  </div>
                  <div class="row">
                    <h2 id="totalResultadoDS"></h2>
                  </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <small class="small">
                    <span id="totalPendenteResultadoDS">.</span>
                  </small>
                  <small>
                    <strong>
                      <span id="totalQuitadoResultadoDS">.</span>
                    </strong>
                  </small>
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
      <!-- <div class="card-footer">
        <div class="text-right ">
          <button type="submit" class="btn btn-dark shadow">Fechar o Mês</button>
        </div>
      </div> -->
              
    </div>
    <!-- Fim do Painel do Dashboard-->

  </div>
</main>
