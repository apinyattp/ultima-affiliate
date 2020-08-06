<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    <?php echo strtoupper($type) ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo site_url('document/api') ?>">API</a></li>
                    <li><a href="<?php echo site_url('document/sitemap') ?>">Sitemap</a></li>
                    <li class="divider"></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <?php
                ksort($a_data);
                foreach ($a_data as $key => $data) {
                    $this->load->view('document/template/subview/mainmenu', ['key' => $key, 'path' => $key, 'a_data' => $data]);
                }
                ?>
            </ul>
        </div>
    </div>
</nav>