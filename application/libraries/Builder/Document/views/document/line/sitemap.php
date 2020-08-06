<?php
$method = strtolower($line['data']['method']);
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
$url = '';

$builder = \Builder\Builder::get_instance();
$endpoint = $builder->get_endpoint($line['data']['method'], $line['value']);
if(!empty($endpoint)) {
    $file = $endpoint->get_file();
    $file = trim($file, '/');
    $hash = strtolower($method.'-'.$endpoint->get_endpoint());
    $url = 'document/api/'.$file.'#'.$hash;
}

?>
<div class="panel panel-default" >
    <div class="panel-heading type-<?php echo $a_class[$method] ?>" id="<?php echo $method.'-'.$line['value'] ?>" >
        <span class="btn btn-outline btn-<?php echo $a_class[$method] ?> btn-xs"><?php echo $a_name[$method] ?></span>
        <?php if(empty($url)){ ?>
            <span><?php echo htmlspecialchars($line['value']) ?></span>
        <?php }else{ ?>
            <a href="<?php echo site_url($url) ?>"><?php echo htmlspecialchars($line['value']) ?></a>
        <?php } ?>
    </div>
    <div class="panel-body">
