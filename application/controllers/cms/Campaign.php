<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $a_header_data = [
            'page' => 'campaign',
            'a_admin' => $a_admin
        ];

        $this->load->library('accesstrade');
        $result = $this->accesstrade->campaigns();

        $campaigns = $this->format->map('cms/campaign/list', $result);

        $a_data = [
            'campaigns' => $campaigns
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/list', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function quicklink($campaignId) {
        $this->load->library('accesstrade');
        $result = $this->accesstrade->quicklink($campaignId);
    }

}
