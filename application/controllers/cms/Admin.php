<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('cookie');
        $this->load->helper('form');
    }

    public function index() {
        $this->load->view('cms/login');
    }

    public function login() {
        if($this->_is_login()) {
            redirect('cms/banner');
        }else{
            $this->load->view('cms/login');
        }
    }

}
