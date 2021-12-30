<?php if (!defined('ABSPATH')) exit;
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>
<!--Utilizo o sufixo ED nos ids dos campos para indicar que eles pertencem Editar Despesa-->

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Editar Despesa</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>Despesas">Despesas</a></li>
      <li class="breadcrumb-item active">Editar</li>
    </ol>

    <!--Hidden Inputs -->
    <input type="text" class="hidden" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input type="text" class="hidden" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input type="text" class="hidden" id="txtDespesaID" value="">
    <input type="text" class="hidden" id="txtDataVencimentoDespesa" value="">
    <input type="text" class="hidden" name="pagina" id="pagina" value="editarDespesa">

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">
      <?php
        // Lista as categorias de despesas
        $listar_categorias = $modelo->listar_categorias("Despesa");
      ?>
      <div class="card-body">
      <!-- Corpo do Painel Principal -->
      <!-- <form id="formIncluirNovaDespesa"> -->
        <form id="formCabecalhoDespesaED">
        <!-- ------------------------------------------------------------------------------------------------------------------- -->
        <div class="form-row">
          <div class="form-group col-12 text-right">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="chkDespesaFixaED" name="chkDespesaFixaED" disabled>
              <label class="custom-control-label" for="chkDespesaFixaED"><small><strong>Despesa Fixa</strong></small></label>
            </div>
          </div>

          <div class="form-group col-md-8" id="agrupamentoCampoDescricaoED">
            <small class="mt-2"><strong>Descrição:</strong></small>
            <input type="text" class="form-control form-control-sm" id="txtDescricaoED" name="txtDescricaoED" required>
          </div>
          <div class="form-group col-md-4" id="agrupamentoCampoCategoriaED">
            <small class="mt-4"><strong>Categoria:</strong></small>
            <div class="input-group input-group-sm">
              <select class="form-control form-control-sm" id="selCategoriaED" name="selCategoriaED" required>
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

        <div class="form-row" id="agrupamentoCamposVencimentoValorED">
          <div class="form-group col-lg-5 col-6">
            <small class="mt1"><strong>Vencimento:</strong></small>
            <input type="date" class="form-control form-control-sm" id="txtVencimentoED" name="txtVencimentoED" value="" required>
          </div>
          <div class="form-group col-lg-5 col-6">
            <small class="mt-1"><strong>Valor:</strong></small>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control form-control-sm mask-money" id="txtValorED" name="txtValorED" value="" required>
            </div>
          </div>
          <!-- <div class="form-group col-lg-2 col-4">
            <small class="mt-1"><strong>Parcelas:</strong></small>
            <input type="number" class="form-control form-control-sm"  id="txtParcelasED" name="txtParcelasED" value="1" required>
          </div> -->
        </div>

        <!-- Não utilizado-->
        <div class="col-md-2 hidden">
          <div class="form-group">
            <small class="mt-4"><strong>Entrada:</strong></small>
            <input type="text" class="form-control form-control-sm moeda" id="txtEntradaED" name="txtEntradaED" value="0" required />
          </div>
        </div>

        <!-- <div class="row">
          <div class="col-12 text-right">
            <small class="mt-4"><strong></strong></small>
            <button type="submit" class="btn btn-info" id="btnGerarParcelasED">Gerar Parcelas</button>
          </div>
        </div> -->
        </form>
        <hr> <!-- ------------------------------------------------------------------------------------------------------------ -->

        <!--Incluir/Alterar Parcelas Individualmente-->
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#collapseCriarAlterarParcelaED" id="btnCollapseCriarAlterarParcelaED" aria-expanded="false" aria-controls="collapseExample">Criar/Alterar Parcela</button>
        <div class="collapse" id="collapseCriarAlterarParcelaED">
          <div class="card card-body bg-light">
          <!--Corpo do painel de Criar/Alterar Parcela-->
          <form id="formParcelaDespesaED">
            <div class="form-row">
              <div class="form-group col-md-8">
                <small class="mt-4"><strong>Descrição da parcela:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtDescricaoParcelaED" name="txtDescricaoParcelaED" value="" required>
              </div>
              <div class="form-group col-md-4">
                <small class="mt-4"><strong>Categoria:</strong></small>
                <div class="input-group input-group-sm">
                  <select class="form-control form-control-sm" id="selCategoriaParcelaED" name="selCategoriaParcelaED" required>
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
                <input type="text" class="form-control form-control-sm" id="txtNumeroParcelaED" name="txtNumeroParcelaED" value="" readonly>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Vencimento:</strong></small>
                <input type="date" class="form-control form-control-sm" id="txtVencimentoParcelaED" name="txtVencimentoParcelaED" value="" required>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Valor:</strong></small>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                  </div>
                  <input type="text" class="form-control form-control-sm mask-money" id="txtValorParcelaED" name="txtValorParcelaED" value="" required>
                </div>
              </div>

              <div class="form-group col-12">
                <small class="mt-4"><strong>Código de barras:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtCodigoDeBarrasParcelaED" name="txtCodigoDeBarrasParcelaED" value="">
              </div>

              <div class="form-group col-12 mt-0">
                <small class="mt-0"><strong>Observações:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtObservacoesParcelaED" name="txtObservacoesParcelaED" value="">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12 mt-0 text-right">
                <small class="mt-0"><strong></strong></small>
                <nobr>
                  <button type="button" class="btn btn-danger btn" id="btnCancelarInclusaoParcelaED"><i class="fas fa-times"></i></button>
                  <button type="submit" class="btn btn-info btn" id="btnIncluirAlterarParcelaED"><i class="fas fa-check"></i></button>
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
                <table class="table table-sm display compact table-hover table-bordered" id="tabelaParcelasED" width="100%" cellspacing="0">
                  <thead class="thead-light">
                    <tr>
                      <th class = "">Parcela</th>
                      <th>Descricao</th>
                      <th>Vencimento</th>
                      <th>Valor</th>
                      <th class = "">Categoria</th>
                      <th class = "">CodigoDeBarras</th>
                      <th class = "">Observacoes</th>
                      <th class = "">Excluir</th>
                    </tr>
                  </thead>
                  <tbody id="tabelaParcelasBodyED">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      <!-- FIM Corpo do Painel Principal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="LimparCamposIncluirDespesa()">Limpar</button>
        <button type="button" class="btn btn-success" id="btnSalvarDespesaED">Salvar</button>
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
              <option value="Receita">Receita</option>
              <option value="Despesa" selected>Despesa</option>
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

<!-- Modal Deletar Parcela-->
<div class="modal fade" id='modal-deletar-parcela-despesa' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="modalDeletarParcelaDespesaTitleED"></strong>?</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body modalDeleteAlinhar">
        <!--Corpo do modal-->
        <input type="text" class="form-control" id="txtModalExcluirNumeroParcelaED"  value="">
        <img class="modalDelete" src="<?php echo HOME_URI ?>views/_images/delete.jpg" alt="">
        <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Não</button>
        <a class="btn btn-danger" onclick="DeletarParcelaDespesaED()">Sim</a>
      </div>

    </div>
  </div>
</div>
<!-- FIM Modal Deletar Parcela-->