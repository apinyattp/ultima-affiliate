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

?>
<div class="panel panel-default" >
    <div class="panel-heading type-<?php echo $a_class[$method] ?>" id="<?php echo $method.'-'.$line['value'] ?>" >
        <span class="btn btn-outline btn-<?php echo $a_class[$method] ?> btn-xs"><?php echo $a_name[$method] ?></span>
        <?php echo htmlspecialchars($line['value']) ?>
        <button class="btn btn-outline btn-primary btn-xs pull-right btn-try">try</button>
    </div>
    <div class="panel-footer" style="display:none;">
        <?php $this->load->view('document/explorer', ['method' => $line['data']['method'], 'endpoint' => $line['value']]); ?>
    </div>
    <div class="panel-body">