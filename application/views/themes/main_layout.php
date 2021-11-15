
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="description" content="Hexacode System">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@ridhwangs">
    <meta property="twitter:creator" content="@ridhwangs">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Hexacode - SURYAPUTRA SARANA PT.">
    <meta property="og:title" content="Hexacode - SURYAPUTRA SARANA PT.">

    <meta property="og:description" content="Interface System">
    <title>MyPOS - <?= $page_header; ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script src="<?= base_url(); ?>node_modules/jquery/dist/jquery.min.js"></script>

    <!-- Main CSS-->
    <link rel="stylesheet" href="<?= base_url(); ?>node_modules/bootstrap/dist/css/bootstrap.css" crossorigin="anonymous">
    <script src="<?= base_url(); ?>node_modules/bootstrap/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!-- pace loading -->
    <link rel="stylesheet" href="<?= assets_url(); ?>vendor/pace/v1.0.0/themes/green/pace-theme-minimal.css" crossorigin="anonymous" />

    <!-- Font-icon css-->
    <link rel="stylesheet" href="<?= assets_url(); ?>vendor/fontawesome-free-5.11.2-web/css/all.min.css" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= assets_url(); ?>vendor/select2-4.0.11/dist/css/select2.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= base_url(); ?>node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css" crossorigin="anonymous" />

    <link rel="stylesheet" href="<?= base_url(); ?>node_modules/sweetalert2/dist/sweetalert2.min.css" />

    <link rel="stylesheet" href="<?= assets_url(); ?>vendor/animate/animate.css" />
    
    <!-- <script src="<?= base_url(); ?>node_modules/jquery-tabledit-1.2.3/jquery.tabledit.js"></script> -->

    <link rel="stylesheet" type="text/css" href="<?= assets_url(); ?>css/main.css">


    <script src="<?= assets_url(); ?>vendor/select2-4.0.11/dist/js/select2.full.min.js" crossorigin="anonymous"></script>


    

  </head>
  <body class="app sidenav-toggled rtl" data-senna="data-senna" data-senna-surface="data-senna-surface">
    <div id="cover-spin"></div>
    <!-- Navbar-->
    <?php
      if ($this->ion_auth->logged_in()) {
      $user = $this->ion_auth->user()->row();
      $perusahaan = $this->crud_model->read('perusahaan')->row();
    ?>
    <footer class="fixed-bottom" style="border-top: 1px solid red;">
      <div class="container-fluid bg-light">
        <ul class="navbar-nav">
          <li class="nav-item text-right">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></li>
        </ul>
      </div>
    </footer>

      <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark app-header">

        <a class="app-sidebar__toggle" href="javascript:void(0)" data-toggle="sidebar" aria-label="Hide Sidebar" onclick="w3_open()">
          <i class="fa fa-bars" style="font-size:30px; padding: 10px;"></i>
        </a>

        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button> -->

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto" id="menu-atas">
            <li class="nav-item ">
              <a class="nav-link" href="<?= site_url('beranda') ?>"><i class="app-menu__icon fas fa-chart-pie"></i> Beranda <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('inventory') ?>"><i class="app-menu__icon fas fa-dolly-flatbed"></i> Inventory</a>
            </li>
            <li class="nav-item dropdown active">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="app-menu__icon fas fa-dolly-flatbed"></i> Transaksi
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?= site_url('transaksi/masuk') ?>">Transaksi Masuk</a>
                <a class="dropdown-item" href="<?= site_url('transaksi/keluar') ?>">Transaksi Keluar</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('pengaturan/perusahaan') ?>"><i class="app-menu__icon fas fa-cogs"></i> Setting Perusahaan</a>
            </li>
          </ul>

          <ul class="navbar-nav ml-auto">
        
             <?php
               $this->load->view('themes/include/nav-item');
              ?>
          </ul>
        </div>
      </nav>
      <div>
        <aside class="app-sidebar" style="z-index:99999" id="mySidebar">
          <ul class="app-menu" style="padding-bottom:20px;">
            <a href="javascript:void(0)" data-toggle="sidebar" onclick="w3_close()" style="background: #e84118;text-align:right; padding:3px 3px;" class="app-menu__item treeview-indicator">
              <span aria-hidden="true" class="app-menu__label">Tutup (X)</span>
            </a>
          </ul>
          <div class="app-sidebar__user row d-flex justify-content-center">
            <img class="app-sidebar__user-avatar" src="<?= base_url('assets/logo/'. $perusahaan->logo) ?>" width="150px" alt="Logo Image">
       
          </div>
          <!-- Page Content -->

          <ul class="app-menu">
            <li><a class="app-menu__item" href="<?= site_url('beranda') ?>"><i class="app-menu__icon fas fa-chart-pie"></i><span class="app-menu__label">Beranda</span></a></li>
            <li><a class="app-menu__item" href="<?= site_url('inventory') ?>"><i class="app-menu__icon fas fa-dolly-flatbed"></i><span class="app-menu__label">Inventory</span></a></li>
            <li class="treeview"><a class="app-menu__item" href="javascript:void(0)" data-toggle="treeview"><i class="app-menu__icon fa fa-exchange-alt"></i><span class="app-menu__label">Transaksi</span><i class="treeview-indicator fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?= site_url('transaksi/masuk') ?>"><i class="icon fas fa-long-arrow-alt-right"></i> Transaksi Masuk</a></li>
                <li><a class="treeview-item" href="<?= site_url('transaksi/keluar') ?>"><i class="icon fas fa-long-arrow-alt-right"></i> Transaksi Keluar</a></li>
              </ul>
            </li>
            <li><a class="app-menu__item" href="<?= site_url('pengaturan/perusahaan') ?>"><i class="app-menu__icon fas fa-cogs"></i><span class="app-menu__label">Setting Perusahaan</span></a></li>
            <li><hr style="border: 1px dashed white; "></li>
             <li><a class="app-menu__item" href="<?= site_url('auth/logout') ?>"><i class="app-menu__icon fas fa-sign-out-alt"></i><span class="app-menu__label">Logout</span></a></li>
          </ul>
        </aside>
      </div>

    <div class="w3-overlay w3-animate-opacity"  data-toggle="sidebar" onclick="w3_close()" style="cursor:pointer;" id="myOverlay"></div>

    <main class="app-content" role="main">
        <?= $output; ?>
    </main>

    <?php
      }else{
    ?>
       <section class="material-half-bg">
          <div class="cover bg-primary">

          </div>
        </section>

        <section class="login-content">
           <?= $output; ?>
        </section>
    <?php
      }
    ?>


    <!-- The javascript plugin to display page loading on top-->
    <script data-pace-options='{ "ajax": false }' src="<?= assets_url(); ?>vendor/pace/v1.0.0/pace.min.js" crossorigin="anonymous"></script>

    <!-- Data table plugin-->
    <script type="text/javascript" src="<?= assets_url(); ?>js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= assets_url(); ?>js/plugins/dataTables.bootstrap.min.js"></script>

    <!-- InputMask -->
    <script src="<?= assets_url(); ?>vendor/jquery-maskedinput/dist/jquery.maskedinput.js"></script>

    <!-- Vendor plugin-->
    <script src="<?= assets_url(); ?>vendor/autoNumeric/autoNumeric.js"></script>


    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?= assets_url(); ?>js/plugins/bootstrap-notify.min.js"></script>
    <script src="<?= assets_url(); ?>js/main.js"></script>

    <script src="<?= base_url(); ?>node_modules/sweetalert2/dist/sweetalert2.min.js" crossorigin="anonymous"></script>

  </body>
</html>
