<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('accesstrade', $data);
    }

    public function accesstrade() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('accesstrade', $data);
    }
    
    public function admitad() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('admitad', $data);
    }

}
