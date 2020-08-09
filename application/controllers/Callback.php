<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = json_encode($_GET);
        $post_data = json_encode($this->input->post());

        $this->load->model('callback_model');
        $this->callback_model->create($data.$post_data);
    }

}
