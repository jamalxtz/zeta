<?php if ( ! defined('ABSPATH')) exit; 
  date_default_timezone_set('America/Sao_Paulo');
  $data = date('Y/m/d');
  $hora = date('h:i a');
?>

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Receitas</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>finances">Finances</a></li>
      <li class="breadcrumb-item active">Receitas</li>
    </ol>
    
    <input class="hidden" type="text" id="idURL" value="<?php echo HOME_URI?>models/finances/ajax-finances-model.php">
    <input class="form-control py-4 hidden" name="pagina" id="pagina" type="text" value="horta">

    <!-- Painel do Painel Principal -->
    <div class="card shadow mb-4">

      <?php 
        // Lista os dados
        $lista_receitas = $modelo->listar_receitas( $parametros );
        $lista_total = $modelo->listar_total_receitas( $parametros );
        // Crrega os métodos que alteram os dados
        $modelo->excluir_receita(); 
        $modelo->incluir_receita(); //Mesmo método utilizado para incluir e editar
        $modelo->quitar_receita();
        $modelo->estornar_receita();
      ?>
      
      <div class="card-body">
      <!-- Corpo do Painel Principal -->
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span data-toggle="modal" data-target="#add-receita">
              <button type="button" href="" class="btn btn-success btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Add Receita">+<i class="fas fa-hand-holding-usd mr-1"></i>
              </button>
            </span>

            <span data-toggle="modal" data-target="#add-conta-a-receber">
              <button type="button" href="" class="btn btn-success btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Add Conta à Receber" disabled>+<i class="fas fa-calendar mr-1"></i>
              </button>
            </span>
          </div>  
          
            <a type="button" href="<?php echo HOME_URI?>finances" class="btn btn-dark btn-lg" role="button" data-toggle="tooltip" data-placement="top" title="Voltar"><i class="fas fa-reply mr-1"></i></a>
        </div>
        <p class="form_success"></p>
        <br>

        <div class="row">

          <div class="col-xl-6">
            <!--Painel de Receitas-->
            <div class="card mb-4">
              <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-chart-bar mr-1"></i>Receitas</span>
                Março, 2021
              </div>
              <div class="card-body">
              <!--Inicio do corpo do painel-->
              
                <div class="table-responsive">
                  <table class="table table-sm display compact table-hover table-bordered" id="" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                      <tr>
                        <th style="display: none">ID</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Ação</th>
                      </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($lista_receitas as $fetch_userdata): ?>
                      <tr class="<?php echo $modelo->verifica_status($fetch_userdata['quitado'], $fetch_userdata['vencimento']) ?>">
                        <!-- ID -->
                        <td style="display: none"> <?php echo $fetch_userdata['id'] ?> </td>
                        <!-- Descrição -->
                        <td><nobr><?php echo $fetch_userdata['descricao'] ?></nobr></td>
                        <!-- Valor -->
                        <td>
                          <nobr>
                            <?php if($fetch_userdata['quitado'] == "SIM"){
                                echo '<strong>R$ '.$modelo->formatar_valor($fetch_userdata['valorquitado']).'</strong>';
                              }else{
                                echo "<span class='text-muted'>R$ ".$modelo->formatar_valor($fetch_userdata['valorpendente'])."</span>";
                              }
                            ?>
                          </nobr>
                        </td>
                        <!-- Ação -->
                        <td class="text-center"> 
                            <nobr>
                            <?php if ($fetch_userdata['quitado'] == "SIM"): ?>
                              <button class="btn btn-info btn-sm" disabled><i class="fas fa-pen"></i></button>
                              <a href="" class="btn btn-secondary btn-sm" role="button" data-toggle="modal" data-target="#modal-estornar<?php echo $fetch_userdata['id'] ?>"><strong class="ml-1 mr-1">E</strong></a>
                            <?php else: ?>
                              <a href="" class="btn btn-info btn-sm" role="button" data-toggle="modal" data-target="#editar-receita<?php echo $fetch_userdata['id'] ?>"><i class="fas fa-pen"></i></a>
                              <a href="" class="btn btn-success btn-sm" role="button" data-toggle="modal" data-target="#modal-quitar<?php echo $fetch_userdata['id'] ?>"><i class="fas fa-dollar-sign ml-1 mr-1"></i></a>
                            <?php endif; ?>
                          </nobr>
                        </td>
                      </tr>

                      <!--Modal Quitar-->
                      <div class="modal fade" id='modal-quitar<?php echo $fetch_userdata['id'] ?>' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" class="form-control mask-money" id="QRvalorquitado" name="QRvalorquitado" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" required>
                                  </div>
                                </div>
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Data de Quitação:</strong></small>
                                  <input type="date" class="form-control" id="QRquitacao" name="QRquitacao" value="<?php echo $modelo->formatar_data($data); ?>" required>
                                </div>
                              </div>

                            <!--FIM Corpo do modal-->
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-success" name="quitarReceitaBTN" value="quitarReceita">Quitar</button>   
                            </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <!--FIM do modal Quitar-->

                      <!--Modal Estornar-->
                      <div class="modal fade" id='modal-estornar<?php echo $fetch_userdata['id'] ?>' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                              <input class="hidden" type="text" id="id" name="id" value="<?php echo $fetch_userdata['id'] ?>">

                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <small class="mt-4"><strong>Descrição:</strong></small>
                                  <input type="text" class="form-control" id="NRdescricao" name="NRdescricao" required value="<?php echo $fetch_userdata['descricao'] ?>" readonly>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Valor Pendente:</strong></small>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">R$</div>
                                    </div>
                                    <input type="text" class="form-control mask-money" id="NRvalor" name="NRvalor" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" readonly>
                                    
                                  </div>
                                </div>
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Vencimento:</strong></small>
                                  <input type="date" class="form-control" id="NRvencimento" name="NRvencimento" value="<?php echo $modelo->formatar_data($fetch_userdata['vencimento']); ?>" readonly>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="NRfixo" id="NRfixo" <?php if($fetch_userdata['fixo'] == "SIM"){ echo "checked";}?> disabled>
                                  <label class="form-check-label" for="NRfixo">
                                    <small class="mt-4"><strong>Receita Fixa</strong></small>
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
                                    <input type="text" class="form-control mask-money" id="QRvalorquitado" name="QRvalorquitado" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" readonly>
                                  </div>
                                </div>
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Data de Quitação:</strong></small>
                                  <input type="date" class="form-control" id="QRquitacao" name="QRquitacao" value="<?php echo $modelo->formatar_data($fetch_userdata['quitacao']); ?>" readonly>
                                </div>
                              </div>

                            <!--FIM Corpo do modal-->
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-danger" name="estornarReceitaBTN" value="estornarReceita">Estornar</button>   
                            </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <!--FIM do modal Estornar-->

                      <!--Modal Editar -->
                      <div class="modal fade" id='editar-receita<?php echo $fetch_userdata['id'] ?>' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-lg" role="document">
                          <div class="modal-content">

                            <div class="modal-header">
                              <p class="modal-title" id="myModalLabel">Editar Receita</p>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            <div class="modal-body">
                            <!--Corpo do modal-->
                              <form enctype="multipart/form-data" method="post" action="">

                              <input class="hidden" type="text" id="id" name="id" value="<?php echo $fetch_userdata['id'] ?>">

                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <small class="mt-4"><strong>Descrição:</strong></small>
                                  <input type="text" class="form-control text-capitalize" id="NRdescricao" name="NRdescricao" value="<?php echo $fetch_userdata['descricao'] ?>" required>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Valor:</strong></small>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">R$</div>
                                    </div>
                                    <input type="text" class="form-control mask-money" id="NRvalor" name="NRvalor" value="<?php echo $modelo->formatar_valor($fetch_userdata['valorpendente']); ?>" required>
                                    
                                  </div>
                                </div>
                                <div class="form-group col-6">
                                  <small class="mt-4"><strong>Vencimento:</strong></small>
                                  <input type="date" class="form-control" id="NRvencimento" name="NRvencimento" value="<?php echo $modelo->formatar_data($fetch_userdata['vencimento']); ?>" required>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="NRfixo" id="NRfixo" <?php if($fetch_userdata['fixo'] == "SIM"){ echo "checked";}?>>
                                  <label class="form-check-label" for="NRfixo">
                                    <small class="mt-4"><strong>Receita Fixa</strong></small>
                                  </label>
                                </div>
                              </div>

                            <!--FIM Corpo do modal-->
                            </div>

                            <div class="modal-footer d-flex align-items-center justify-content-between">
                              <button type="submit" class="btn btn-danger" name="excluirReceitaBTN" value="excluirReceita"><i class="fas fa-trash"></i></button>

                              <div>
                                <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" name="incluirReceitaBTN" value="incluirReceita">Salvar</button>  
                              </div> 
                            </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <!--Fim Modal Editar -->
                                                  
                    <?php endforeach;?>
                    </tbody>
                  </table>
                </div>

              <!--FIM do corpo do painel-->
              </div>
              <div class="card-footer d-flex align-items-center justify-content-between">
                <?php foreach ($lista_total as $fetch_userdata): ?>

                  <small class="small text-muted" data-toggle="tooltip" data-placement="top" title="Total Pendente">R$ 
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
            <!--FIM Painel de Receitas-->
          </div>

          <div class="col-xl-6">
            <!--Painel de Gráficos-->
            <div class="card mb-4">
              <div class="card-header"><i class="fas fa-chart-area mr-1"></i>Gráfico</div>
              <div class="card-body">
                <canvas id="graficoReceitas" width="100%" height="60"></canvas>
              </div>
            </div>
            <!--FIM Painel de Gráficos-->
          </div>

        </div>
                  
        
      <!-- FIM Corpo do Painel Principal -->
      </div>
              
    </div>
    <!-- Fim do Painel Principal -->

  </div>
</main>


<!--Modal Add Receita-->
<div class="modal fade" id='add-receita' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <p class="modal-title" id="myModalLabel">Nova Receita</p>
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
            <input type="text" class="form-control text-capitalize" id="NRdescricao" name="NRdescricao" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-6">
            <small class="mt-4"><strong>Valor:</strong></small>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
              </div>
              <input type="text" class="form-control mask-money" id="NRvalor" name="NRvalor" value="" required>
              
            </div>
          </div>
          <div class="form-group col-6">
            <small class="mt-4"><strong>Vencimento:</strong></small>
            <input type="date" class="form-control" id="NRvencimento" name="NRvencimento" value="" required>
          </div>
        </div>

        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="NRfixo" id="NRfixo">
            <label class="form-check-label" for="NRfixo">
              <small class="mt-4"><strong>Receita Fixa</strong></small>
            </label>
          </div>
        </div>

      <!--FIM Corpo do modal-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" name="incluirReceitaBTN" value="incluirReceita">Salvar</button>   
      </div>
      </form>

    </div>
  </div>
</div>
<!--Fim Modal Add Receita -->

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
            <input type="text" class="form-control" id="NRdescricao" name="NRdescricao" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-6">
            <small class="mt-4"><strong>Valor:</strong></small>
            <div class="input-group">
              <input type="text" class="form-control mask-money" id="NRvalor" name="NRvalor" value="">
              <div class="input-group-append">
                <div class="input-group-text">R$</div>
              </div>
            </div>
          </div>
          <div class="form-group col-6">
            <small class="mt-4"><strong>Vencimento:</strong></small>
            <input type="date" class="form-control" id="NRvencimento" name="NRvencimento" value="">
          </div>
        </div>

        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="NRfixo" id="NRfixo">
            <label class="form-check-label" for="NRfixo">
              <small class="mt-4"><strong>Receita Fixa</strong></small>
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