<?php
$method = strtolower($method);
$hash = strtolower($method.'-'.$endpoint->get_endpoint());
$a_class = [
    'get' => 'success',
    'post' => 'danger',
    'get_post' => 'warning',
    'put' => 'warning',
];
$a_name = [
    'get' => 'get',
    'post' => 'post',
    'get_post' => 'get/post',
    'put' => 'put',
];
?>
<li>
    <a href="<?php echo site_url('document/'.$type.'/'.$path."#".$hash) ?>" alt="<?php echo $endpoint->get_endpoint() ?>" title="<?php echo $endpoint->get_endpoint() ?>">
        <span class="btn btn-outline btn-<?php echo $a_class[$method] ?> btn-xs"><?php echo $a_name[$method] ?></span>
        <?php echo basename($endpoint->get_endpoint()) ?>
    </a>
</li>