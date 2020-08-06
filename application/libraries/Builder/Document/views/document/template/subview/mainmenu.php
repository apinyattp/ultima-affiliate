<?php
$active = substr($file, 1, strlen($path)) == $path;
?>
<li <?php echo $active ? 'class="active"' : ''?>>
    <a href="<?php echo site_url($path) ?>"><?php echo $key ?><span class="fa arrow"></span></a>
    <ul class="nav <?php echo $active ? 'in' : ''?>">
    <?php
    ksort($a_data);
    foreach ($a_data as $key => $data) {
        if(is_array($data)){
            $this->load->view('document/template/subview/mainmenu', ['key' => $key, 'path' => $path.'/'.$key, 'a_data' => $data]);
        }elseif($type == 'api'){
            foreach($data->get_endpoint() as $endpoint_path => $a_endpoint){
                foreach($a_endpoint as $method => $endpoint){
                    $this->load->view('document/template/subview/submenu', ['method' => $method, 'endpoint' => $endpoint, "path" => $path]);
                }
            }
        }elseif($type == 'sitemap') {
            foreach($data->get_page() as $page){
                $this->load->view('document/template/subview/submenu', ['method' => '', 'endpoint' => $page, "path" => $path]);
            }
        }
    }
    ?>
    </ul>
</li>