<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $a_header_data = [
            'page' => 'dashboard',
            'a_admin' => $a_admin
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/dashboard');
        $this->load->view('cms/template/footer');
    }

}
