<?php 

    $get = $_GET;
    $remove = ['page', 'perpage'];
    array_diff_key($get, array_flip($remove));
    $http_build_query = http_build_query($get);

    $page = $page;
    $perpage = $perpage;
    $url = base_url() . $path . '?'.$http_build_query.'&perpage='.$perpage.'&';
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-end">
    <li class="page-item">
        <a href="<?php echo $url . 'page=1'?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == 1) ? 'disabled' : ''; ?>>
            First
        </button>
        </a>
    </li>
    <li class="page-item">
        <a href="<?php echo $url . 'page=' .( $page - 1); ?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == 1) ? 'disabled' : ''; ?>>
            &laquo;
        </button>
        </a>
    </li>
    <?php for($i=1; $i<=$total_page; $i++) { ?>
        <?php if(abs($page - $i) > 3) { 
            if(abs($page - $i) == 4) echo '<button class="btn btn-primary btn-link" disabled> ... </button>';  
            continue;
        } ?>
        <li class="page-item">  
            <a href="<?php echo $url . 'page=' . $i; ?>">
                <button class="btn btn-primary btn-link" <?php echo ($page == $i) ? 'disabled' : ''; ?>>
                <?php echo $i; ?>
                </button>
            </a>
        </li>
    <?php } ?>
    <li class="page-item">
        <a href="<?php echo $url . 'page=' .( $page + 1); ?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == $total_page) ? 'disabled' : ''; ?>>&raquo;</button>
        </a>
    </li>
    <li class="page-item">
        <a href="<?php echo $url . 'page=' . $total_page ?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == $total_page) ? 'disabled' : ''; ?>>
            Last
        </button>
        </a>
    </li>
    </ul>
</nav>