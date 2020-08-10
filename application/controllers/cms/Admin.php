<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('cms/login');
    }
    
    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $this->load->model('module/admin/admin_model');
        $a_admin = $this->admin_admin_model->login($username, $password);

        $this->load->helper('cookie');

        if($a_admin === FALSE) {
            setcookie('error_code', E::AUTH_LOGIN_INVALID_USERNAME_OR_PASSWORD, time() + 5);
            redirect('cms/admin');
        }

        $this->load->library('module/admin/authorization');
        $result = $this->format->run('module/admin/admin/login_success', $a_admin);

        setcookie('utoken', $result['token'], time() + 28800, '/');
        redirect('cms/dashboard');
    }

    public function logout() {
        $this->load->helper('cookie');
        delete_cookie('utoken');
        redirect('cms/admin');
    }

}
