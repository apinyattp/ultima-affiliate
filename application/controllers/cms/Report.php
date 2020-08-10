<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/list');
        $this->load->view('cms/template/footer');
    }

}
