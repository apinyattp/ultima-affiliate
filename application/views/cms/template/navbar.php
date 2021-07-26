<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
    <a href="https://www.ultimalife.co.th/" class="simple-text logo-normal text-center">
        Jelala - Affiliate
    </a>
    </div>
    <div class="sidebar-wrapper overflow-hidden">
        <ul class="nav">
            <?php if ($a_admin['role'] == 'admin'){ ?>
                <li class="<?php echo ($page == 'campaign') ? 'active' : ''?>">
                    <a href="<?php echo site_url('cms/campaign');?>">
                        <i class="nc-icon nc-diamond"></i>
                        <p>Campaign</p>
                    </a>
                </li>
            <?php } ?>
            <li class="<?php echo ($page == 'report') ? 'active' : ''?>">
                <a href="<?php echo site_url('cms/report');?>">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Conversion Report</p>
                </a>
            </li>
            <li class="<?php echo ($page == 'missing_conversion') ? 'active' : ''?>">
                <a href="<?php echo site_url('cms/missing_conversion');?>">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Missing Conversion</p>
                </a>
            </li>
        </ul>
    </div>
</div>
