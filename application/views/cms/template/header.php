<!DOCTYPE html>
<html lang="en">
  <head>
      <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/0bd485de6c.js" crossorigin="anonymous"></script>
    <!-- Material Kit CSS -->
    <?php echo $this->head->css_get(); ?>
    <!-- JS -->
    <script type="text/javascript">
      var base_url = "<?php echo base_url() ?>"
    </script>
    <?php echo $this->head->js_get() ?>
    <title>Jelala Affiliate</title>
  </head>
  <body>
  <div class="wrapper h-100">
    <?php $this->load->view('cms/template/navbar', ['page' => $page]) ?>
    <div class="main-panel h-100">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler" data-target="#side-navbar">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand text-uppercase" href="javascript:;"><?php echo $page; ?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
              <li class="nav-item m-auto">
                <span class="text-uppercase"><?php echo $a_admin['username']; ?></span>
              </li>
              <li class="nav-item btn-rotate dropdown">
                <a class="nav-link dropdown-toggle mx-auto" href="javascript:;" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  <i class="nc-icon nc-settings-gear-65"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="<?php echo site_url('cms/admin/logout');?>">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">


