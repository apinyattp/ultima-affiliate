<!DOCTYPE html>
<html lang="en">
  <head>
      <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- Material Kit CSS -->
    <link href="<?php echo asset_base_url()?>paper/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset_base_url()?>paper/assets/css/paper-dashboard.css">
    <title>Sign in Jelala Affiliate</title>
  </head>
  <body>
    <div class="wrapper container">
      <div class="row d-flex h-100 align-items-center">
        <div class="col-md-6 mx-auto">
          <h2>Jelala Affiliate</h2>
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Sign in</h5>
              <?php if(isset($_COOKIE['error_code']) && $_COOKIE['error_code'] == '3008'){ ?><div class="alert alert-danger" role="alert">Invalid username or password</div><?php } ?>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('cms/admin/login');?>" method="post" autocomplete="off">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                  </div>
                  <div class="row">
                    <button class="btn btn-primary col-md-4 mx-auto">sign in</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>

    </div>
    <script src="<?php echo asset_base_url()?>paper/assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo asset_base_url()?>paper/assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo asset_base_url()?>paper/assets/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo asset_base_url()?>paper/assets/js/plugins/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo asset_base_url()?>paper/assets/js/plugins/moment.min.js"></script>
  </body>
</html>
