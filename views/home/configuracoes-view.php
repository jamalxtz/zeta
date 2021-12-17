<?php if ( ! defined('ABSPATH')) exit; ?>

<main class='bg-color'>
  <div class="container-fluid">
      
    <h1 class="mt-4">Configurações</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="<?php echo HOME_URI?>">Painel</a></li>
      <li class="breadcrumb-item active">Configurações</li>
    </ol>

    <div class="row">
    </div>

    <div class="card shadow mb-4">
      <div class="card-header">
        <i class="fas fa-cog mr-1"></i>Configurações
      </div>
      <div class="card-body">
        <input type="text" class="hidden" id="urlPadrao" name="url-edit" value="<?php echo HOME_URI?>">
        <canvas id="myAreaChart" width="100%" height="30"></canvas>
        <div>
          <button class="btn btn-primary" onclick="buscarAtendimento('<?php echo HOME_URI?>models/monitor/teste-ajax.php')">Teste de Requisição AJAX</button>
        </div>
      </div>
      <div class="card-footer small text-muted">Atualizado hoje as 11:59 PM</div>
    </div>

  </div>
</main>