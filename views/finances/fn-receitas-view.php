<?php if (!defined('ABSPATH')) exit;
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>
<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Receitas</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Receitas</li>
    </ol>

    <!-- Hidden Inputs -->
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input class="hidden" type="text" id="urlEditarReceita" value="<?php echo HOME_URI ?>receitas/editar">
    <input class="hidden" type="text" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <!-- <input class="hidden" type="text" id="dataParametro" value="<?php print_r($parametros[0] . $parametros[1]) ?>"> -->
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="receitas">

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">
      <div class="card-header d-flex justify-content-between">
        <div><i class="fas fa-chart-area mr-1"></i>Análise Mensal</div>
        <div class="col-lg-3 col-md-4 col-6">
          <!--form-control-->
          <input type="month" class="form-control form-control-sm inputDataReferencia" id="txtDataReferenciaRC" name="txtDataReferenciaRC" value="">
        </div>
      </div>
      <div class="card-body">
        <!-- Corpo do Painel Principal -->
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <a type="button" href="<?php echo HOME_URI ?>receitas/incluir" class="btn btn-success btn" role="button" data-toggle="tooltip" data-placement="top" title="Incluir Receita">+<i class="fas fa-credit-card mr-1"></i></a>
            <a type="button" href="<?php echo HOME_URI ?>categorias" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Categorias"><i class="fas fa-list-ul mr-1"></i></a>
            <a type="button" href="<?php echo HOME_URI ?>relatorios/receitas" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Relatórios"><i class="fas fa-chart-line mr-1"></i></a>
          </div>
          <button type="button" href="" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Atualizar" onclick="ListarReceitasFixasSemParcela()"><i class="fas fa-sync-alt mr-1"></i></button>
          <!-- <a type="button" href="<?php echo HOME_URI ?>finances" class="btn btn-dark btn" role="button" data-toggle="tooltip" data-placement="top" title="Voltar"><i class="fas fa-reply mr-1"></i></a> -->
        </div>
        <p class="form_success"></p>
        <br>

        <div class="row">

          <div class="col-xl-6">
            <!--Painel de Receitas-->
            <div class="table-responsive">

              <table class="table table-sm display compact table-hover table-bordered" id="tabelaReceitasRC" width="100%" cellspacing="0">
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
                <tbody id="tabelaReceitasBodyRC">
                </tbody>
              </table>
            </div>

          </div>
          <!--FIM Painel de Receitas-->

          <div class="col-xl-6">
            <!--Painel de Gráficos-->
            <div class="charts" id="grafico-receitas"></div>
            <!--FIM Painel de Gráficos-->
          </div>

        </div>
      <!-- FIM Corpo do Painel Principal -->
      </div>
      <div class="card-footer bg-dark text-white text-center">
        <h3>Total: <strong id="totalReceitasMensalRC"></strong></h3>
      </div>

    </div>
    <!-- Fim do Painel Principal -->

  </div>
</main>

<!--Modal Quitar-->
<div class="modal fade" id='modal-quitar-receita' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Quitar Receita?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalDeleteAlinhar">
      <!--Corpo do modal-->
      <form id="formModalQuitarReceitaRC">

        <!-- Inputs Ocultos -->
        <input class="hidden" type="text" id="txtIdModalQuitarReceitaRC" name="txtIdModalQuitarReceitaRC" value="">
        <input class="hidden" type="text" id="txtQtdeParcelasModalQuitarReceitaRC" name="txtQtdeParcelasModalQuitarReceitaRC" value="">
        <input class="hidden" type="text" id="txtVencimentoModalQuitarReceitaRC" name="txtVencimentoQuitarReceitaRC" value="">

        <div id="alert_placeholder"></div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Data de Quitação:</strong></small>
            <input type="date" class="form-control" id="txtDataQuitacaoModalQuitarReceitaRC" name="txtDataQuitacaoModalQuitarReceitaRC" value="" required>
          </div>
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Valor Quitado:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control mask-money" id="txtValorQuitadoModalQuitarReceitaRC" name="txtValorQuitadoModalQuitarReceitaRC" value="" required>
            </div>
          </div>
        </div>
      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnQuitarModalQuitarReceitaRC">Quitar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Quitar-->

<!--Modal Estornar-->
<div class="modal fade" id='modal-estornar-receita' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Estornar Receita?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--Corpo do modal-->
        <form id="formModalEstornarReceitaRC">

          <!-- Inputs Ocultos -->
          <input class="hidden" type="text" id="txtIdModalEstornarReceitaRC" name="txtIdModalEstornarReceitaRC" value="">
          <input class="hidden" type="text" id="txtQtdeParcelasModalEstornarReceitaRC" name="txtQtdeParcelasModalEstornarReceitaRC" value="">
          <input class="hidden" type="text" id="txtVencimentoModalEstornarReceitaRC" name="txtVencimentoModalEstornarReceitaRC" value="">
          <input class="hidden" type="text" id="txtValorPendenteModalEstornarReceitaRC" name="txtVencimentoModalEstornarReceitaRC" value="">

          <div class="form-row">
            <div class="form-group col-md-12">
              <small class="mt-4"><strong>Descrição:</strong></small>
              <input type="text" class="form-control" id="txtDescricaoModalEstornarReceitaRC" name="txtDescricaoModalEstornarReceitaRC" required value="" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-lg-6">
              <small class="mt-4"><strong>Data de Quitação:</strong></small>
              <input type="date" class="form-control" id="txtQuitacaoModalEstornarReceitaRC" name="txtQuitacaoModalEstornarReceitaRC" value="" readonly>
            </div>
            <div class="form-group col-lg-6">
              <small class="mt-4"><strong>Valor Quitado:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="txtQuitadoModalEstornarReceitaRC" name="txtQuitadoModalEstornarReceitaRC" value="" readonly>
              </div>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger" id="btnEstornarModalEstornarReceitaRC">Estornar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Estornar-->

<!--Modal Receitas Fixas-->
<div class="modal fade" id='modal-receitas-fixas' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Importar Receitas Fixas?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!--Corpo do modal-->
      <form id="formModalReceitasFixasRC">

        <!-- Inputs Ocultos -->
        <input class="hidden" type="text" id="txtIdModalReceitasFixasRC" name="txtIdModalReceitasFixasRC" value="">
        <input class="hidden" type="text" id="txtQtdeParcelasModalReceitasFixasRC" name="txtQtdeParcelasModalReceitasFixasRC" value="">
        <input class="hidden" type="text" id="txtVencimentoModalReceitasFixasRC" name="txtVencimentoModalReceitasFixasRC" value="">

        <div id="alertModalReceitasFixasRC"></div>

        <div class="table-responsive">
          <table class="table table-sm display compact table-hover table-bordered" id="tabelaReceitasFixasRC" width="100%" cellspacing="0">
            <thead class="thead-dark">
              <tr>
                <th class="hidden">ID</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th class="hidden">Vencimento</th>
                <th class="hidden">Categoria</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody id="tabelaReceitasFixasBodyRC">
            </tbody>
          </table>
        </div>

      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnImportarModalReceitasFixasRC">Importar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Receitas Fixas-->









<!--Esse modal está aqui por gambiarra, trocar o evento do botão de acionar modal para click-->
<div class="modal fade" id='modal-editar-receita' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Quitar Receita?</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalDeleteAlinhar">
      <!--Corpo do modal-->
      <form id="formModalQuitarReceitaRC">

        <!-- Inputs Ocultos -->
        <input class="hidden" type="text" id="txtIdModalQuitarReceitaRC" name="txtIdModalQuitarReceitaRC" value="">
        <input class="hidden" type="text" id="txtQtdeParcelasModalQuitarReceitaRC" name="txtQtdeParcelasModalQuitarReceitaRC" value="">
        <input class="hidden" type="text" id="txtVencimentoModalQuitarReceitaRC" name="txtVencimentoQuitarReceitaRC" value="">

        <div id="alert_placeholder"></div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Data de Quitação:</strong></small>
            <input type="date" class="form-control" id="txtDataQuitacaoModalQuitarReceitaRC" name="txtDataQuitacaoModalQuitarReceitaRC" value="" required>
          </div>
          <div class="form-group col-md-6">
            <small class="mt-4"><strong>Valor Quitado:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control mask-money" id="txtValorQuitadoModalQuitarReceitaRC" name="txtValorQuitadoModalQuitarReceitaRC" value="" required>
            </div>
          </div>
        </div>
      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="btnQuitarModalQuitarReceitaRC">Quitar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Quitar-->