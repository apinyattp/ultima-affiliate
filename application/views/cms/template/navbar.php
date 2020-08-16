<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
    <!-- <a href="https://www.ultimalife.co.th/" class="simple-text logo-mini">
        <div class="logo-image-small">
        <img src="../assets/img/logo-small.png">
        </div>
    </a> -->
    <a href="https://www.ultimalife.co.th/" class="simple-text logo-normal text-center">
        Ultima life - Affiliate
    </a>
    </div>
    <div class="sidebar-wrapper overflow-hidden">
        <ul class="nav">
            <li class="<?php echo ($page == 'dashboard') ? 'active' : ''?>">
            <a href="<?php echo site_url('cms/dashboard');?>">
                <i class="nc-icon nc-bank"></i>
                <p>Dashboard</p>
            </a>
            </li>
            <li class="<?php echo ($page == 'campaign') ? 'active' : ''?>">
            <a href="<?php echo site_url('cms/campaign');?>">
                <i class="nc-icon nc-diamond"></i>
                <p>Campaign</p>
            </a>
            </li>
            <li class="<?php echo ($page == 'report') ? 'active' : ''?>">
            <a href="<?php echo site_url('cms/report');?>">
                <i class="nc-icon nc-pin-3"></i>
                <p>Report</p>
            </a>
            </li>
            <li class="<?php echo ($page == 'user') ? 'active' : ''?>">
                <a href="<?php echo site_url('cms/user');?>">
                    <i class="nc-icon nc-single-02"></i>
                    <p>User</p>
                </a>
            </li>
        </ul>
    </div>
</div>
