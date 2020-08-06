<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['jwt_key'] = '';
$config['token_timeout'] = 1;

if(empty($config['jwt_key'])) {
    $jwt_file = APPPATH.'config/jwt.b64';
    if(file_exists($jwt_file)) {
        $config['jwt_key'] = file_get_contents($jwt_file);
    }elseif(is_writable($jwt_file)) {
        $config['jwt_key'] = bin2hex(random_bytes(10));
        file_put_contents($jwt_file, $config['jwt_key']);
    }else{
        $config['jwt_key'] = $_SERVER['SERVER_NAME'];
    }
}

/* End of file jwt.php */
/* Location: ./application/config/jwt.php */
