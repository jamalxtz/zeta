<?php if (!defined('ABSPATH')) exit;
  //funcao que limita o numero de caracteres que vai ser utilizada na tabela e em outros locais do codigo
  //ex de uso:  echo limita_caracteres("Mensagem de teste para testar o script.", 10); // Resultado: Mensagem d...
  //ex de uso: echo limita_caracteres("Mensagem de teste para testar o script.", 10, false); // Resultado: Mensagem...
  function limita_caracteres($texto, $limite, $quebra = true){
    $tamanho = strlen($texto);
    if ($tamanho <= $limite) { //Verifica se o tamanho do texto é menor ou igual ao limite
      $novo_texto = $texto;
    } else { // Se o tamanho do texto for maior que o limite
      if ($quebra == true) { // Verifica a opção de quebrar o texto
        $novo_texto = trim(substr($texto, 0, $limite)) . "...";
      } else { // Se não, corta $texto na última palavra antes do limite
        $ultimo_espaco = strrpos(substr($texto, 0, $limite), " "); // Localiza o útlimo espaço antes de $limite
        $novo_texto = trim(substr($texto, 0, $ultimo_espaco)) . ""; // Corta o $texto até a posição localizada
      }
    }
    return $novo_texto; // Retorna o valor formatado
  }
?>

<main class="bg-color">
  <div class="container-fluid">

    <h1 class="mt-4">Usuários</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI ?>">Painel</a></li>
      <li class="breadcrumb-item active">Usuários</li>
    </ol>

    <div class="row">
      <div class="col-xl-12">

        <!-- Painel dos Usuários-->
        <div class="card shadow mb-4">

          <div class="card-body">
            <!-- Corpo do painel -->
            <div class="text-right">
              <a type="button" href="<?php echo HOME_URI ?>usuario/novo" class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="top" title="Novo Usuário">+<i class="fas fa-user mr-1"></i></a>
            </div>
            <p class="form_success"></p>
            <br>

            <?php
            // Lista os usuários
            $lista = $modelo->pegar_lista_usuarios();
            // Carrega o método que deleta Usuarios
            $modelo->del_user($parametros);
            ?>

            <div class="table-responsive">
              <table class="table table-sm display compact table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">

                <thead class="thead-dark">
                  <tr>
                    <th style="display: none">ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Situação</th>
                    <th>Ação</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($lista as $fetch_userdata) : ?>
                    <tr>
                      <td style="display: none"> <?php echo $fetch_userdata['id'] ?> </td>
                      <td>
                        <nobr> <a href="<?php echo HOME_URI ?>usuario/visualizar/edit/<?php echo $fetch_userdata['id'] ?>" class="btn btn-link  btn-xs" role="button"><?php echo limita_caracteres($fetch_userdata['nome'], 20, true) ?> </a></nobr>
                      </td>
                      <td> <?php echo $fetch_userdata['email'] ?> </td>
                      <td class="text-center">
                        <?php
                        if ($fetch_userdata['situacao'] == 'Ativo') {
                          echo '<span class="badge badge-info">Ativo</span>';
                        } else {
                          echo '<span class="badge badge-secondary">Inativo</span>';
                        }
                        ?>
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <nobr>
                            <a href="" class="btn btn-danger btn-sm" role="button" data-toggle="modal" data-target="#modal-delete<?php echo $fetch_userdata['id'] ?>"><i class="fas fa-trash mr-1"></i></span></a>
                            <a href="<?php echo HOME_URI ?>usuario/editar/edit/<?php echo $fetch_userdata['id'] ?>" class="btn btn-success btn-sm" role="button"><i class="fas fa-pen mr-1"></i></span></a>
                          </nobr>
                        </div>
                      </td>
                    </tr>

                    <!-- Modal Deletar Usuário-->
                    <div class="modal fade" id='modal-delete<?php echo $fetch_userdata['id'] ?>' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog  modal-md" role="document">
                        <div class="modal-content">

                          <div class="modal-header">
                            <p class="modal-title" id="myModalLabel">Deseja realmente excluir <strong><?php echo $fetch_userdata['nome'] ?></strong>?</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body modalDeleteAlinhar">
                            <!--Corpo do modal-->
                            <img class="modalDelete" src="<?php echo HOME_URI ?>views/_images/delete.jpg" alt="">
                            <!--FIM Corpo do modal-->
                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal" )">Não</button>
                            <a class="btn btn-danger" href="<?php echo HOME_URI ?>usuario/index/del/<?php echo $fetch_userdata['id'] ?>">Sim</a>
                          </div>

                        </div>
                      </div>
                    </div>

                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <!-- FIM Corpo do painel -->
          </div>

        </div>
        <!-- Fim do Painel dos Usuários-->

      </div>
    </div>

  </div>
</main>