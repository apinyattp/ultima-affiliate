<?php
$builder = \Builder\Builder::get_instance();
$endpoint = $builder->get_endpoint($method, $endpoint);

if(empty($endpoint)) return;

$url = $endpoint->get_url();
$a_header = $endpoint->get_header();
$a_input = $endpoint->get_input();

?>
<form method="<?php echo $method ?>" action="<?php echo $url ?>" class="form-explorer">
<?php
if(!empty($a_header)){
    ?>
    <h4>- Header</h4>
    <div class="header-group" data-header="<?php echo htmlspecialchars(json_encode($a_header)) ?>"></div>
    <?php
    echo '<hr>';
}
?>

<h4>- INPUT - <button type="button" class="btn btn-primary btn-switch">Switch input</button></h4>
<?php
$a_body = array();
$json_data = array();
foreach($a_input as $var_name => $validate) {
    $input = new \Builder\Input\Input($var_name, $validate);
    $a_body[] = array(
        'name' => $input->var_name(),
        'validate' => $input->validate_string(),
    );
    foreach ($input->json_data() as $key => $json) {
        $json_data[$key] = $json;
    }
}
?>
    <div class="body-group" data-input_type="form" data-body="<?php echo htmlspecialchars(json_encode($a_body)) ?>" data-json="<?php echo htmlspecialchars(json_encode($json_data)) ?>"></div>
    <button type="submit" class="btn btn-primary send">Send</button>
    <pre class="result" style="display:none;"></pre>
</form>