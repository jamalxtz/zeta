<?php if (!defined('ABSPATH')) exit;
date_default_timezone_set('America/Sao_Paulo');
$data = date('Y/m/d');
$hora = date('h:i a');
?>
<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Selecionar Despesas</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item "><a href="<?php echo HOME_URI ?>despesas">Despesas</a></li>
      <li class="breadcrumb-item active">Selecionar</li>
    </ol>

    <!-- Hidden Inputs -->
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input class="hidden" type="text" id="urlEditarDespesa" value="<?php echo HOME_URI ?>despesas/editar">
    <input class="hidden" type="text" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input class="hidden" type="text" id="dataParametro" value="<?php print_r($parametros[0] . $parametros[1]) ?>">
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="selecionarDespesas">

    <!-- Painel Principal -->
    <div class="card shadow mb-4">
      <div class="card-header d-flex justify-content-between">
        <div><i class="fas fa-chart-area mr-1"></i>Despesas</div>
      </div>
      <div class="card-body">
        <!-- Corpo do Painel Principal -->
        <form id="formFiltroSelecionarDespesas">
          <!-- ------------------------------------------------------------------------------------------------------------------- -->
          <div class="form-row">
            <div class="form-group col-6 col-md-3">
              <small class="mt-2"><strong>Data Inicial:</strong></small>
              <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataInicialSD" name="txtDataInicialSD" value="">
            </div>
            <div class="form-group col-6 col-md-3">
              <small class="mt-2"><strong>Data Final:</strong></small>
              <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataFinalSD" name="txtDataFinalSD" value="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-8">
              <small class="mt-2"><strong>Descri????o:</strong></small>
              <input type="text" class="form-control form-control-sm" id="txtDescricaoSD" name="txtDescricaoSD" required>
            </div>
            <div class="form-group col-md-4">
              <small class="mt-4"><strong>Categoria:</strong></small>
              <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" id="selCategoriaSD" name="selCategoriaSD" required>
                  <option selected></option>
                  <?php foreach ($listar_categorias as $fetch_userdata) : ?>
                    <option value="<?php echo $fetch_userdata['id'] ?>">
                      <?php echo $fetch_userdata['descricao'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 text-right">
              <small class="mt-4"><strong></strong></small>
              <button type="submit" class="btn btn-info" id="btnGerarParcelasSD"><i class="fas fa-search mr-1"></i> Buscar</button>
            </div>
          </div>
        </form>
        <hr> <!-- ------------------------------------------------------------------------------------------------------------ -->


        <div class="row">

          <div class="col-xl-12">
            <!--Painel de Despesas-->
            <div class="table-responsive">

              <table class="table table-sm display compact table-hover table-bordered" id="tabelaDespesasSD" width="100%" cellspacing="0">
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
                    <th>Descri????o</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th class="hidden">Quitado</th>
                    <th class="hidden">Qtde Parcelas</th>
                    <th>A????o</th>
                  </tr>
                </thead>
                <tbody id="tabelaDespesasBodySD">
                </tbody>
              </table>
            </div>

          </div>
          <!--FIM Painel de Despesas-->

        </div>
        <!-- FIM Corpo do Painel Principal -->
      </div>
      <div class="card-footer bg-dark text-white text-center">
        <h3>Total: <strong id="totalDespesasMensalSD"></strong></h3>
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
              <small class="mt-4"><strong>Data de Quita????o:</strong></small>
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
              <small class="mt-4"><strong>Descri????o:</strong></small>
              <input type="text" class="form-control" id="txtDescricaoModalEstornarDespesaDP" name="txtDescricaoModalEstornarDespesaDP" required value="" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-lg-6">
              <small class="mt-4"><strong>Data de Quita????o:</strong></small>
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

<!--Modal Despesas Fixas-->
<div class="modal fade" id='modal-despesas-fixas' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Importar Despesas Fixas?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--Corpo do modal-->
        <form id="formModalDespesasFixasDP">

          <!-- Inputs Ocultos -->
          <input class="hidden" type="text" id="txtIdModalDespesasFixasDP" name="txtIdModalDespesasFixasDP" value="">
          <input class="hidden" type="text" id="txtQtdeParcelasModalDespesasFixasDP" name="txtQtdeParcelasModalDespesasFixasDP" value="">
          <input class="hidden" type="text" id="txtVencimentoModalDespesasFixasDP" name="txtVencimentoModalDespesasFixasDP" value="">

          <div id="alertModalDespesasFixasDP"></div>

          <div class="table-responsive">
            <table class="table table-sm display compact table-hover table-bordered" id="tabelaDespesasFixasDP" width="100%" cellspacing="0">
              <thead class="thead-dark">
                <tr>
                  <th class="hidden">ID</th>
                  <th>Descri????o</th>
                  <th>Valor</th>
                  <th class="hidden">Vencimento</th>
                  <th class="hidden">Categoria</th>
                  <th>A????o</th>
                </tr>
              </thead>
              <tbody id="tabelaDespesasFixasBodyDP">
              </tbody>
            </table>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnImportarModalDespesasFixasDP">Importar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Despesas Fixas-->









<!--Esse modal est?? aqui por gambiarra, trocar o evento do bot??o de acionar modal para click-->
<div class="modal fade" id='modal-editar-despesa' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Aguarde</strong></p>
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

          <div class="d-flex justify-content-center">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <!--FIM Corpo do modal-->
      </div>

      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnQuitarModalQuitarDespesaDP">Quitar</button>
      </div> -->
      </form>

    </div>
  </div>
</div>
<!--FIM do modal editar despesa-->