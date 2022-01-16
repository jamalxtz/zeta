<?php if (!defined('ABSPATH')) exit;
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>
<!--Utilizo o sufixo NR nos ids dos campos para indicar que eles pertencem Nova Receita-->

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Incluir Receita</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>receitas">Receitas</a></li>
      <li class="breadcrumb-item active">Incluir</li>
    </ol>

    <input type="text" class="hidden" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input type="text" class="hidden" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input type="text" class="hidden" id="dataParametro" value="<?php print_r($parametros[0] . $parametros[1]) ?>">
    <input type="text" class="form-control py-4 hidden" name="pagina" id="pagina" value="incluirReceita">

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">

      <?php
        // Lista as categorias de receitas
        $listar_categorias = $modelo->listar_categorias("Receita");
      ?>

      <div class="card-body">
      <!-- Corpo do Painel Principal -->
      <!-- <form id="formIncluirNovaReceita"> -->
        <form id="formCabecalhoReceitaNR">
        <!-- ------------------------------------------------------------------------------------------------------------------- -->
        <div class="form-row">
          <div class="form-group col-12 text-right">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="chkReceitaFixaNR" name="chkReceitaFixaNR">
              <label class="custom-control-label" for="chkReceitaFixaNR"><small><strong>Receita Fixa</strong></small></label>
            </div>
          </div>

          <div class="form-group col-md-8">
            <small class="mt-2"><strong>Descrição:</strong></small>
            <input type="text" class="form-control form-control-sm" id="txtDescricaoNR" name="txtDescricaoNR" required>
          </div>
          <div class="form-group col-md-4">
            <small class="mt-4"><strong>Categoria:</strong></small>
            <div class="input-group input-group-sm">
              <select class="form-control form-control-sm" id="selCategoriaNR" name="selCategoriaNR" required>
                <option selected></option>
                <?php foreach ($listar_categorias as $fetch_userdata) : ?>
                  <option value="<?php echo $fetch_userdata['id'] ?>">
                    <?php echo $fetch_userdata['descricao'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#modalCadastrarCategoria"><i class="fas fa-plus mr-1"></i></button>
              </div>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-lg-5 col-6">
            <small class="mt1"><strong>Vencimento:</strong></small>
            <input type="date" class="form-control form-control-sm" id="txtVencimentoNR" name="txtVencimentoNR" value="" required>
          </div>
          <div class="form-group col-lg-5 col-6">
            <small class="mt-1"><strong>Valor:</strong></small>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control form-control-sm mask-money" id="txtValorNR" name="txtValorNR" value="" required>
            </div>
          </div>
          <div class="form-group col-lg-2 col-4">
            <small class="mt-1"><strong>Parcelas:</strong></small>
            <input type="number" class="form-control form-control-sm"  id="txtParcelasNR" name="txtParcelasNR" value="1" required>
          </div>
        </div>

        <!-- Não utilizado-->
        <div class="col-md-2 hidden">
          <div class="form-group">
            <small class="mt-4"><strong>Entrada:</strong></small>
            <input type="text" class="form-control form-control-sm moeda" id="txtEntradaNR" name="txtEntradaNR" value="0" required />
          </div>
        </div>

        <div class="row">
          <div class="col-12 text-right">
            <small class="mt-4"><strong></strong></small>
            <button type="submit" class="btn btn-info" id="btnGerarParcelasNR">Gerar Parcelas</button>
          </div>
        </div>
        </form>
        <hr> <!-- ------------------------------------------------------------------------------------------------------------ -->

        <!--Incluir/Alterar Parcelas Individualmente-->
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#collapseCriarAlterarParcelaNR" id="btnCollapseCriarAlterarParcelaNR" aria-expanded="false" aria-controls="collapseExample">Criar/Alterar Parcela</button>
        <div class="collapse" id="collapseCriarAlterarParcelaNR">
          <div class="card card-body bg-light">
          <!--Corpo do painel de Criar/Alterar Parcela-->
          <form id="formParcelaReceitaNR">
            <div class="form-row">
              <div class="form-group col-md-8">
                <small class="mt-4"><strong>Descrição da parcela:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtDescricaoParcelaNR" name="txtDescricaoParcelaNR" value="" required>
              </div>
              <div class="form-group col-md-4">
                <small class="mt-4"><strong>Categoria:</strong></small>
                <div class="input-group input-group-sm">
                  <select class="form-control form-control-sm" id="selCategoriaParcelaNR" name="selCategoriaParcelaNR" required>
                    <option selected></option>
                    <?php foreach ($listar_categorias as $fetch_userdata) : ?>
                      <option value="<?php echo $fetch_userdata['id'] ?>">
                        <?php echo $fetch_userdata['descricao'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div class="input-group-append">
                    <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#modalCadastrarCategoria"><i class="fas fa-plus mr-1"></i></button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group hidden col-12">
                <small class="mt-4"><strong>Parcela:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtNumeroParcelaNR" name="txtNumeroParcelaNR" value="" readonly>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Vencimento:</strong></small>
                <input type="date" class="form-control form-control-sm" id="txtVencimentoParcelaNR" name="txtVencimentoParcelaNR" value="" required>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Valor:</strong></small>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                  </div>
                  <input type="text" class="form-control form-control-sm mask-money" id="txtValorParcelaNR" name="txtValorParcelaNR" value="" required>
                </div>
              </div>

              <div class="form-group col-12">
                <small class="mt-4"><strong>Código de barras:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtCodigoDeBarrasParcelaNR" name="txtCodigoDeBarrasParcelaNR" value="">
              </div>

              <div class="form-group col-12 mt-0">
                <small class="mt-0"><strong>Observações:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtObservacoesParcelaNR" name="txtObservacoesParcelaNR" value="">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12 mt-0 text-right">
                <small class="mt-0"><strong></strong></small>
                <nobr>
                  <button type="button" class="btn btn-danger btn" id="btnCancelarInclusaoParcelaNR"><i class="fas fa-times"></i></button>
                  <button type="submit" class="btn btn-info btn" id="btnIncluirAlterarParcelaNR"><i class="fas fa-check"></i></button>
                </nobr>
              </div>
            </div>
          </form>          
          <!--FIM do corpo do painel de Criar/Alterar Parcela-->
          </div>
        </div>
        <!--FIM Incluir/Alterar Parcelas Individualmente-->

        <!--Tabela que mostra as parcelas geradas-->
        <div class="row mt-0">
          <div class="col-12">
            <div class="clearfix form-actions">
              <div class="table-responsive">
                <table class="table table-sm display compact table-hover table-bordered" id="tabelaParcelasNR" width="100%" cellspacing="0">
                  <thead class="thead-light">
                    <tr>
                      <th>Parcela</th>
                      <th class = "hidden">Descricao</th>
                      <th>Vencimento</th>
                      <th>Valor</th>
                      <th class = "hidden">Categoria</th>
                      <th class = "hidden">CodigoDeBarras</th>
                      <th class = "hidden">Observacoes</th>
                    </tr>
                  </thead>
                  <tbody id="tabelaParcelasBodyNR">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      <!-- FIM Corpo do Painel Principal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="LimparCamposIncluirReceita()">Limpar</button>
        <button type="button" class="btn btn-success" id="btnSalvarReceitaNR">Salvar</button>
      </div>
      <!-- </form> -->
    </div>
    <!-- Fim do Painel Principal -->
  </div>
</main>

<!--Modal Cadastrar Categoria-->
<div class="modal fade" id='modalCadastrarCategoria' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel"><strong>Cadastrar Categoria</strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!--Corpo do modal-->
      <form id="formCadastrarCategoriaNC">
        <div class="form-row">
          <div class="form-group col-12">
            <small class="mt-4"><strong>Descrição</strong></small>
            <input type="text" class="form-control text-capitalize" id="txtDescricaoCategoriaNC" name="txtDescricaoCategoriaNC" value="" required>
          </div>

          <div class="form-group col-12">
            <small class="mt-4"><strong>Tipo</strong></small>
            <select class="form-control form-control-sm" id="selTipoCategoriaNC" name="selTipoCategoriaNC" disabled>
              <option value="Receita" selected>Receita</option>
              <option value="Despesa">Despesa</option>
              <option value="Investimento">Investimento</option>
            </select>
          </div>
        </div>
      <!--FIM Corpo do modal-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" name="btnCadastrarCategoriaNC" value="btnCadastrarCategoriaNC">Salvar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Cadastrar Categoria-->