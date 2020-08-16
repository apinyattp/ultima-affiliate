<?php 
    $page = $page;
    $perpage = $perpage;
    $url = base_url() . $path . '?perpage='.$perpage.'&';
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-end">
    <li class="page-item">
        <a href="<?php echo $url . 'page=' .( $page - 1); ?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == 1) ? 'disabled' : ''; ?>>
            Previous
        </button>
        </a>
    </li>
    <?php for($i=1; $i<=$total_page; $i++) { ?>
        <li class="page-item">
        <a href="<?php echo $url . 'page=' . $i; ?>">
            <button class="btn btn-primary btn-link <?php echo ($page == $i) ? 'disabled' : ''; ?>">
            <?php echo $i; ?>
            </button>
        </a>
        </li>
    <?php } ?>
    <li class="page-item">
        <a href="<?php echo $url . 'page=' .( $page + 1); ?>">
        <button class="btn btn-primary btn-link" <?php echo ($page == $total_page) ? 'disabled' : ''; ?>>Next</button>
        </a>
    </li>
    </ul>
</nav>