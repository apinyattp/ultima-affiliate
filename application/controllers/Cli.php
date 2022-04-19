<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli extends MY_Controller {

    public function __construct () {
        parent::__construct();
        if(!is_cli()) exit;
    }

    public function generate_token($src) {
        $this->load->helper('jwt');

        $payload = [
            'src' => $src,
            'ptoken' => 'G8V8F',
        ];

        echo jwt_encode($payload);
    }

}
