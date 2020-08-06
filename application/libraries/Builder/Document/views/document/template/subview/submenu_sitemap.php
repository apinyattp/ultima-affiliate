<?php
$page = htmlspecialchars($page);
?>
<li>
    <a href="<?php echo site_url('document/sitemap/'.$path."#".$page) ?>" alt="<?php echo $page ?>" title="<?php echo $page ?>">
        <?php echo $page ?>
    </a>
</li>