<?php if (!defined('ABSPATH')) exit;
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>
<!--Utilizo o sufixo ND nos ids dos campos para indicar que eles pertencem Nova Despesa-->

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Incluir Despesa</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>Despesas">Despesas</a></li>
      <li class="breadcrumb-item active">Incluir</li>
    </ol>

    <input type="text" class="hidden" id="idURL" value="<?php echo HOME_URI ?>models/finances/api-finances-model.php">
    <input type="text" class="hidden" id="userID" value="<?php print_r($_SESSION["userdata"]["id"]); ?>">
    <input type="text" class="hidden" id="dataParametro" value="<?php print_r($parametros[0] . $parametros[1]) ?>">
    <!-- <input type="text" class="form-control py-4 hidden" name="pagina" id="pagina" value="incluirDespesa"> -->

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">

      <?php
        // Lista as categorias de despesas
        $listar_categorias = $modelo->listar_categorias("Despesa");
      ?>

      <div class="card-body">
      <!-- Corpo do Painel Principal -->
        <form enctype="multipart/form-data" method="post" action="">

        <div class="form-row">
          <div class="form-group col-12 text-right">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="chkDespesaFixaND" name="chkDespesaFixaND">
              <label class="custom-control-label" for="chkDespesaFixaND"><small><strong>Despesa Fixa</strong></small></label>
            </div>
          </div>

          <div class="form-group col-md-8">
            <small class="mt-2"><strong>Descrição:</strong></small>
            <input type="text" class="form-control form-control-sm text-capitalize" id="txtDescricaoND" name="txtDescricaoND" required>
          </div>
          <div class="form-group col-md-4">
            <small class="mt-2"><strong>Categoria:</strong></small>
            <select id="selCategoriaND" name="selCategoriaND" class="form-control form-control-sm">
              <option selected></option>
              <?php foreach ($listar_categorias as $fetch_userdata) : ?>
                <option value="<?php echo $fetch_userdata['id'] ?>">
                  <?php echo $fetch_userdata['descricao'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-lg-5 col-6">
            <small class="mt1"><strong>Vencimento:</strong></small>
            <input type="date" class="form-control form-control-sm" id="txtVencimentoND" name="txtVencimentoND" value="" required>
          </div>
          <div class="form-group col-lg-5 col-6">
            <small class="mt-1"><strong>Valor:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><small>R$</small></div>
              </div>
              <input type="text" class="form-control form-control-sm mask-money" id="txtValorND" name="txtValorND" value="" required>
            </div>
          </div>
          <div class="form-group col-lg-2 col-4">
            <small class="mt-1"><strong>Parcelas:</strong></small>
            <input type="number" class="form-control form-control-sm"  id="txtParcelasND" name="txtParcelasND" value="1" required>
          </div>
        </div>

        <!-- Não utilizado-->
        <div class="col-md-2 hidden">
          <div class="form-group">
            <small class="mt-4"><strong>Entrada:</strong></small>
            <input type="text" class="form-control form-control-sm moeda" id="txtEntradaND" name="txtEntradaND" value="0" required />
          </div>
        </div>

        <div class="row">
          <div class="col-12 text-right">
            <small class="mt-4"><strong></strong></small>
            <button type="button" class="btn btn-info" id="btnGerarParcelasND">Gerar Parcelas</button>
          </div>
        </div>

        <hr>

        <!--Incluir/Alterar Parcelas Individualmente-->
        <button type="button" class="btn btn-light" data-toggle="collapse" data-target="#collapseCriarAlterarParcelaND" id="btnCollapseCriarAlterarParcelaND" aria-expanded="false" aria-controls="collapseExample">Criar/Alterar Parcela</button>
        <div class="collapse" id="collapseCriarAlterarParcelaND">
          <div class="card card-body bg-light">
          <!--Corpo do painel de Criar/Alterar Parcela-->
            <div class="form-row">
              <div class="form-group col-md-8">
                <small class="mt-4"><strong>Descrição da parcela:</strong></small>
                <input type="text" class="form-control form-control-sm mask-money" id="txtDescricaoParcelaND" name="txtDescricaoParcelaND" value="">
              </div>
              <div class="form-group col-md-4">
                <small class="mt-4"><strong>Categoria:</strong></small>
                <select class="form-control form-control-sm" id="selCategoriaParcelaND" name="selCategoriaParcelaND" >
                  <option selected></option>
                  <?php foreach ($listar_categorias as $fetch_userdata) : ?>
                    <option value="<?php echo $fetch_userdata['id'] ?>">
                      <?php echo $fetch_userdata['descricao'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group hidden col-12">
                <small class="mt-4"><strong>Parcela:</strong></small>
                <input type="text" class="form-control form-control-sm mask-money" id="txtNumeroParcelaND" name="txtNumeroParcelaND" value="" readonly>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Vencimento:</strong></small>
                <input type="date" class="form-control form-control-sm" id="txtVencimentoParcelaND" name="txtVencimentoParcelaND" value="" required>
              </div>
              <div class="form-group col-6">
                <small class="mt-4"><strong>Valor:</strong></small>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><small>R$</small></div>
                  </div>
                  <input type="text" class="form-control form-control-sm mask-money" id="txtValorParcelaND" name="txtValorParcelaND" value="" required>
                </div>
              </div>

              <div class="form-group col-12">
                <small class="mt-4"><strong>Código de barras:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtCodigoDeBarrasParcelaND" name="txtCodigoDeBarrasParcelaND" value="">
              </div>

              <div class="form-group col-12 mt-0">
                <small class="mt-0"><strong>Observações:</strong></small>
                <input type="text" class="form-control form-control-sm" id="txtObservacoesParcelaND" name="txtObservacoesParcelaND" value="">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12 mt-0 text-right">
                <small class="mt-0"><strong></strong></small>
                <nobr>
                  <button type="button" class="btn btn-danger btn" id="btnCancelarInclusaoParcelaND"><i class="fas fa-times"></i></button>
                  <button type="button" class="btn btn-info btn" id="btnIncluirAlterarParcelaND"><i class="fas fa-check"></i></button>
                </nobr>
              </div>
            </div>

          <!--FIM do corpo do painel de Criar/Alterar Parcela-->
          </div>
        </div>
        <!--FIM Incluir/Alterar Parcelas Individualmente-->

        <!--Tabela que mostra as parcelas geradas-->
        <div class="row mt-0">
          <div class="col-12">
            <div class="clearfix form-actions">
              <div class="table-responsive">
                <table class="table table-sm display compact table-hover table-bordered" id="tabelaParcelasND" width="100%" cellspacing="0">
                  <thead class="thead-light">
                    <tr>
                      <th>Parcela</th>
                      <th class = "">Descricao</th>
                      <th>Vencimento</th>
                      <th>Valor</th>
                      <th class = "">Categoria</th>
                      <th class = "">CodigoDeBarras</th>
                      <th class = "">Observacoes</th>
                    </tr>
                  </thead>
                  <tbody id="tabelaParcelasBodyND">
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
        <button type="button" class="btn btn-success" id="btnSalvarDespesaND">Salvar</button>
      </div>
      </form>
    </div>
    <!-- Fim do Painel Principal -->
  </div>
</main>























<!--Modal Quitar-->
<div class="modal fade" id='modal-quitar' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Quitar <strong><?php echo $fetch_userdata['descricao'] ?></strong>?</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalDeleteAlinhar">
        <!--Corpo do modal-->
        <form enctype="multipart/form-data" method="post" action="">

          <input class="hidden" type="text" id="id" name="id" value="<?php echo $fetch_userdata['id'] ?>">

          <div class="form-row">
            <div class="form-group col-6">
              <small class="mt-4"><strong>Valor Quitado:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="QDvalorquitado" name="QDvalorquitado" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" required>
              </div>
            </div>
            <div class="form-group col-6">
              <small class="mt-4"><strong>Data de Quitação:</strong></small>
              <input type="date" class="form-control" id="QDquitacao" name="QDquitacao" value="<?php echo $modelo->formatar_data($data); ?>" required>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" name="quitarDespesaBTN" value="quitarDespesa">Quitar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Quitar-->

<!--Modal Add Conta à Receber-->
<div class="modal fade" id='add-conta-a-receber' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Conta à Receber</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!--Corpo do modal-->
        <form enctype="multipart/form-data" method="post" action="">

          <div class="form-row">
            <div class="form-group col-md-12">
              <small class="mt-4"><strong>Descrição:</strong></small>
              <input type="text" class="form-control" id="NDdescricao" name="NDdescricao" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <small class="mt-4"><strong>Valor:</strong></small>
              <div class="input-group">
                <input type="text" class="form-control mask-money" id="NDvalor" name="NDvalor" value="">
                <div class="input-group-append">
                  <div class="input-group-text">R$</div>
                </div>
              </div>
            </div>
            <div class="form-group col-6">
              <small class="mt-4"><strong>Vencimento:</strong></small>
              <input type="date" class="form-control" id="NDvencimento" name="NDvencimento" value="">
            </div>
          </div>

          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="NDfixo" id="NDfixo">
              <label class="form-check-label" for="NDfixo">
                <small class="mt-4"><strong>Despesa Fixa</strong></small>
              </label>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM Modal Add Conta à Receber-->

<!--Modal Estornar-->
<div class="modal fade" id='modal-estornar' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Estornar <strong><?php echo $fetch_userdata['descricao'] ?></strong>?</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--Corpo do modal-->
        <form enctype="multipart/form-data" method="post" action="">

          <input class="" type="text" id="modal-estornar-id" name="id" value="">

          <div class="form-row">
            <div class="form-group col-md-12">
              <small class="mt-4"><strong>Descrição:</strong></small>
              <input type="text" class="form-control" id="NDdescricao" name="NDdescricao" required value="<?php echo $fetch_userdata['descricao'] ?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <small class="mt-4"><strong>Valor Pendente:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="NDvalor" name="NDvalor" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" readonly>

              </div>
            </div>
            <div class="form-group col-6">
              <small class="mt-4"><strong>Vencimento:</strong></small>
              <input type="date" class="form-control" id="NDvencimento" name="NDvencimento" value="<?php echo $modelo->formatar_data($fetch_userdata['vencimento']); ?>" readonly>
            </div>
          </div>

          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="NDfixo" id="NDfixo" <?php if ($fetch_userdata['fixo'] == "SIM") {
                                                                                          echo "checked";
                                                                                        } ?> disabled>
              <label class="form-check-label" for="NDfixo">
                <small class="mt-4"><strong>Despesa Fixa</strong></small>
              </label>
            </div>
          </div>

          <hr>

          <div class="form-row">
            <div class="form-group col-6">
              <small class="mt-4"><strong>Valor Quitado:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="QDvalorquitado" name="QDvalorquitado" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" readonly>
              </div>
            </div>
            <div class="form-group col-6">
              <small class="mt-4"><strong>Data de Quitação:</strong></small>
              <input type="date" class="form-control" id="QDquitacao" name="QDquitacao" value="<?php echo $modelo->formatar_data($fetch_userdata['quitacao']); ?>" readonly>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger" name="estornarDespesaBTN" value="estornarDespesa">Estornar</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--FIM do modal Estornar-->

<!--Modal Editar -->
<div class="modal fade" id="modal-editar-despesa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Editar Despesa</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!--Corpo do modal-->
        <form enctype="multipart/form-data" method="post" action="">

          <input class="" type="text" id="modal-editar-id" name="id" value="">

          <div class="form-row">
            <div class="form-group col-md-12">
              <small class="mt-4"><strong>Descrição:</strong></small>
              <input type="text" class="form-control text-capitalize" id="NDdescricao" name="NDdescricao" value="" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <small class="mt-4"><strong>Valor:</strong></small>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">R$</div>
                </div>
                <input type="text" class="form-control mask-money" id="NDvalor" name="NDvalor" value="" required>

              </div>
            </div>
            <div class="form-group col-6">
              <small class="mt-4"><strong>Vencimento:</strong></small>
              <input type="date" class="form-control" id="NDvencimento" name="NDvencimento" value="" required>
            </div>
          </div>

          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="NDfixo" id="NDfixo">
              <label class="form-check-label" for="NDfixo">
                <small class="mt-4"><strong>Despesa Fixa</strong></small>
              </label>
            </div>
          </div>

          <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer d-flex align-items-center justify-content-between">
        <button type="submit" class="btn btn-danger" name="excluirDespesaBTN" value="excluirDespesa"><i class="fas fa-trash"></i></button>

        <div>
          <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success" name="incluirDespesaBTN" value="incluirDespesa">Salvar</button>
        </div>
      </div>
      </form>

    </div>
  </div>
</div>
<!--Fim Modal Editar -->