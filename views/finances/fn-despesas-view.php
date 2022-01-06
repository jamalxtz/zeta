<?php if (!defined('ABSPATH')) exit;
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>
<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Despesas</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Despesas</li>
    </ol>

    <!-- Hidden Inputs -->
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input class="hidden" type="text" id="urlEditarDespesa" value="<?php echo HOME_URI ?>despesas/editar">
    <input class="hidden" type="text" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input class="hidden" type="text" id="dataParametro" value="<?php print_r($parametros[0] . $parametros[1]) ?>">
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="despesas">

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">
      <div class="card-header d-flex justify-content-between">
        <div><i class="fas fa-chart-area mr-1"></i>Análise Mensal</div>
        <div class="col-lg-3 col-md-4 col-6">
          <!--form-control-->
          <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataReferenciaDP" name="txtDataReferenciaDP" value="">
        </div>
      </div>
      <div class="card-body">
        <!-- Corpo do Painel Principal -->
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <a type="button" href="<?php echo HOME_URI ?>despesas/incluir" class="btn btn-danger btn" role="button" data-toggle="tooltip" data-placement="top" title="Incluir Despesa">+<i class="fas fa-credit-card mr-1"></i></a>
            <a type="button" href="<?php echo HOME_URI ?>categorias" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Categorias"><i class="fas fa-list-ul mr-1"></i></a>
            <a type="button" href="<?php echo HOME_URI ?>relatorios/despesas" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Relatórios"><i class="fas fa-chart-line mr-1"></i></a>
          </div>
          <button type="button" href="" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Atualizar" onclick="ListarDespesasMensal()"><i class="fas fa-sync-alt mr-1"></i></button>
          <!-- <a type="button" href="<?php echo HOME_URI ?>finances" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Voltar"><i class="fas fa-reply mr-1"></i></a> -->
        </div>
        <p class="form_success"></p>
        <br>

        <div class="row">

          <div class="col-xl-6">
            <!--Painel de Despesas-->
            <div class="table-responsive">

              <table class="table table-sm display compact table-hover table-bordered" id="tabelaDespesasDP" width="100%" cellspacing="0">
                <caption>
                  <div class="d-flex align-items-center justify-content-between">
                    <small class="small text-muted" data-toggle="tooltip" data-placement="top" title="Total Pendente" id="idTotalPendente"></small>
                    <small>
                      <strong data-toggle="tooltip" data-placement="top" title="Total Quitado" id="idTotalQuitado"></strong>
                    </small>
                  </div>
                </caption>
                <thead class="thead-dark">
                  <tr>
                    <th class="hidden">ID</th>
                    <th>Descrição</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th class="hidden">Quitado</th>
                    <th class="hidden">Qtde Parcelas</th>
                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody id="tabelaDespesasBodyDP">
                </tbody>
              </table>
            </div>

          </div>
          <!--FIM Painel de Despesas-->

          <div class="col-xl-6">
            <!--Painel de Gráficos-->
            <div class="charts" id="grafico-despesas"></div>
            <!--FIM Painel de Gráficos-->
          </div>

        </div>
      <!-- FIM Corpo do Painel Principal -->
      </div>
      <div class="card-footer bg-dark text-white text-center">
        <h3>Total: <strong id="totalDespesasMensalDP"></strong></h3>
      </div>

    </div>
    <!-- Fim do Painel Principal -->

  </div>
</main>

<!--Modal Quitar-->
<div class="modal fade" id='modal-quitar-despesa' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Quitar Despesa?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalDeleteAlinhar">
      <!--Corpo do modal-->
      <form id="formModalQuitarDespesaDP">

        <!-- Inputs Ocultos -->
        <input class="hidden" type="text" id="txtIdModalQuitarDespesaDP" name="txtIdModalQuitarDespesaDP" value="">
        <input class="hidden" type="text" id="txtQtdeParcelasModalQuitarDespesaDP" name="txtQtdeParcelasModalQuitarDespesaDP" value="">
        <input class="hidden" type="text" id="txtVencimentoModalQuitarDespesaDP" name="txtVencimentoQuitarDespesaDP" value="">

        <div id="alert_placeholder"></div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Data de Quitação:</strong></small>
            <input type="date" class="form-control" id="txtDataQuitacaoModalQuitarDespesaDP" name="txtDataQuitacaoModalQuitarDespesaDP" value="" required>
          </div>
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Valor Quitado:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control mask-money" id="txtValorQuitadoModalQuitarDespesaDP" name="txtValorQuitadoModalQuitarDespesaDP" value="" required>
            </div>
          </div>
        </div>
      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnQuitarModalQuitarDespesaDP">Quitar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Quitar-->

<!--Modal Estornar-->
<div class="modal fade" id='modal-estornar-despesa' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Estornar Despesa?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--Corpo do modal-->
        <form id="formModalEstornarDespesaDP">

          <!-- Inputs Ocultos -->
          <input class="hidden" type="text" id="txtIdModalEstornarDespesaDP" name="txtIdModalEstornarDespesaDP" value="">
          <input class="hidden" type="text" id="txtQtdeParcelasModalEstornarDespesaDP" name="txtQtdeParcelasModalEstornarDespesaDP" value="">
          <input class="hidden" type="text" id="txtVencimentoModalEstornarDespesaDP" name="txtVencimentoModalEstornarDespesaDP" value="">
          <input class="hidden" type="text" id="txtValorPendenteModalEstornarDespesaDP" name="txtVencimentoModalEstornarDespesaDP" value="">

          <div class="form-row">
            <div class="form-group col-md-12">
              <small class="mt-4"><strong>Descrição:</strong></small>
              <input type="text" class="form-control" id="txtDescricaoModalEstornarDespesaDP" name="txtDescricaoModalEstornarDespesaDP" required value="" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-lg-6">
              <small class="mt-4"><strong>Data de Quitação:</strong></small>
              <input type="date" class="form-control" id="txtQuitacaoModalEstornarDespesaDP" name="txtQuitacaoModalEstornarDespesaDP" value="" readonly>
            </div>
            <div class="form-group col-lg-6">
              <small class="mt-4"><strong>Valor Quitado:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="txtQuitadoModalEstornarDespesaDP" name="txtQuitadoModalEstornarDespesaDP" value="" readonly>
              </div>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger" id="btnEstornarModalEstornarDespesaDP">Estornar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Estornar-->









<!--Esse modal está aqui por gambiarra, trocar o evento do botão de acionar modal para click-->
<div class="modal fade" id='modal-editar-despesa' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Quitar Despesa?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalDeleteAlinhar">
      <!--Corpo do modal-->
      <form id="formModalQuitarDespesaDP">

        <!-- Inputs Ocultos -->
        <input class="hidden" type="text" id="txtIdModalQuitarDespesaDP" name="txtIdModalQuitarDespesaDP" value="">
        <input class="hidden" type="text" id="txtQtdeParcelasModalQuitarDespesaDP" name="txtQtdeParcelasModalQuitarDespesaDP" value="">
        <input class="hidden" type="text" id="txtVencimentoModalQuitarDespesaDP" name="txtVencimentoQuitarDespesaDP" value="">

        <div id="alert_placeholder"></div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Data de Quitação:</strong></small>
            <input type="date" class="form-control" id="txtDataQuitacaoModalQuitarDespesaDP" name="txtDataQuitacaoModalQuitarDespesaDP" value="" required>
          </div>
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Valor Quitado:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control mask-money" id="txtValorQuitadoModalQuitarDespesaDP" name="txtValorQuitadoModalQuitarDespesaDP" value="" required>
            </div>
          </div>
        </div>
      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnQuitarModalQuitarDespesaDP">Quitar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Quitar-->