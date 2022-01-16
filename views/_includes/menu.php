<?php if ( ! defined('ABSPATH')) exit; ?>

<?php if ( $this->login_required && ! $this->logged_in ) return; ?>

<!-- MENU PRINCIPAL-->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
  <a class="navbar-brand" href="<?php echo HOME_URI;?>"><span class="fz-25 icon-diamond" aria-hidden="true"></span> Zeta Finances</a>
  <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
  <!-- Navbar Search-->
  <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
    <div class="input-group">
      <!-- <a href="<?php echo SITE_URI;?>" target="_blank" class="btn btn-info" type="button">Abrir Site <i class="fas fa-globe"></i></a> -->
    </div>
  </form>
  <!-- Navbar-->
  <ul class="navbar-nav ml-auto ml-md-0">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="<?php echo HOME_URI;?>home/configuracoes"><span class="icon-cog mr-1" aria-hidden="true"></span>Configurações</a>
        <a class="dropdown-item" href="<?php echo HOME_URI;?>home/conta"><span class="icon-user mr-1" aria-hidden="true"></span>Minha conta</a>
        <a class="dropdown-item" href="<?php echo HOME_URI;?>home/notificacoes"><span class="icon-bell mr-1" aria-hidden="true"></span>Notificações</a>
        <a class="dropdown-item" href="" role="button" data-toggle="modal" data-target="#modal-erro"><span class="icon-bug mr-1" aria-hidden="true"></span>Reportar um erro</a>
        <!-- <input class="dropdown-item" type="color" id="colorPicker" value="#ff0000" > -->
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?php echo HOME_URI;?>home/sair"><span class="icon-exit_to_app mr-1" aria-hidden="true"></span>Sair</a>
      </div>
    </li>
  </ul>
</nav>
<!-- FIM MENU PRINCIPAL -->


<div id="layoutSidenav">
  <!-- MENU LATERAL -->
  <div id="layoutSidenav_nav">
    <!-- altere a classe para escolher o modo dark - sb-sidenav-dark ou sb-sidenav-light para o modo claro-->
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <div class="nav">
        <!-- Corpo do Menu Latereal -->
          <div class="sb-sidenav-menu-heading">Principal</div>

          <a class="nav-link" href="<?php echo HOME_URI;?>">
          	<div class="sb-nav-link-icon">
          		<i class="fas fa-tachometer-alt"></i>
          	</div>
            Dashboard
          </a>
          <a class="nav-link" href="<?php echo HOME_URI;?>receitas/">
          	<div class="sb-nav-link-icon">
          		<i class="fas fa-hand-holding-usd"></i>
          	</div>
            Receitas
          </a>
          <a class="nav-link" href="<?php echo HOME_URI;?>despesas/">
          	<div class="sb-nav-link-icon">
          		<i class="fas fa-credit-card"></i>
          	</div>
            Depesas
          </a>
          <!--<a class="nav-link" href="<?php echo HOME_URI;?>monitor">
          	<div class="sb-nav-link-icon">
          		<i class="fas fa-chart-area"></i>
          	</div>
            Monitor
          </a>-->

          <div class="sb-sidenav-menu-heading">Cadastro</div>

          <!-- Exemplo de menu Dropdown
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
          	<div class="sb-nav-link-icon">
          		<i class="fas fa-columns"></i>
          	</div>
            Cadastro
            <div class="sb-sidenav-collapse-arrow">
            	<i class="fas fa-angle-down"></i>
            </div>
          </a>

          <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
            	<a class="nav-link" href="<?php echo HOME_URI;?>produto">Produtos</a>
            	<a class="nav-link" href="<?php echo HOME_URI;?>usuario">Usuários</a>
            </nav>
          </div> -->

          <!-- <a class="nav-link" href="<?php echo HOME_URI;?>produto">
            <div class="sb-nav-link-icon">
              <i class="fas fa-box"></i>
            </div>
            Produtos
          </a> -->
          <a class="nav-link" href="<?php echo HOME_URI;?>usuario">
            <div class="sb-nav-link-icon">
              <span class="icon-user mr-1" aria-hidden="true"></span>
            </div>
            Usuários
          </a>
        <!-- FIM do Corpo do Menu Latereal -->
        </div>
      </div>

      <div class="sb-sidenav-footer">
        <div class="small">Bem-vindo:</div>
        <?php print_r($_SESSION["userdata"]["nome"]);?>
      </div>
    </nav>
  </div>
  <!-- FIM MENU LATERAL -->
  
  <div id="layoutSidenav_content">
