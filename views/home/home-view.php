<?php if ( ! defined('ABSPATH')) exit; ?>

<main class='bg-color'>
  <div class="container-fluid">

    <h1 class="mt-4">Painel</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">Painel</li>
    </ol>

    <!--Atalhos -->
    <div class="row">

      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-6">Inicio</div>
              <div class="col-6 fz-30 text-white text-right"><i class="fas fa-home"></i></div>
            </div>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="<?php echo HOME_URI?>inicio">Acessar</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-6">Sobre</div>
              <div class="col-6 fz-30 text-white text-right"><i class="fas fa-book"></i></div>
            </div>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="<?php echo HOME_URI?>sobre">Acessar</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-6">Contato</div>
              <div class="col-6 fz-30 text-white text-right"><i class="icon-room"></i></div>
            </div>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="<?php echo HOME_URI?>contato">Acessar</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-6">Produtos</div>
              <div class="col-6 fz-30 text-white text-right"><i class="fas fa-box"></i></div>
            </div>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="<?php echo HOME_URI?>produto">Acessar</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

    </div>
    <!--Fim dos Atalhos -->

    <!--Gráficos -->
    <div class="row">
      <div class="col-xl-6">
        <div class="card mb-4">
          <div class="card-header"><i class="fas fa-chart-area mr-1"></i>Visitas Mensais</div>
          <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card mb-4">
          <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Produtos mais vistos</div>
          <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
        </div>
      </div>
    </div>
    <!--Fim dos Gráficos -->
         
  </div>
</main>