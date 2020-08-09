<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct () {
        parent::__construct();
    }

    public function list() {

    }

    public function detail() {
        $uid = $this->input->get('uid');
        $campaign_id = $this->input->get('campaign_id');

        $url = '';

        return $this->_echo_json(E::SUCCESS, ['url' => $url]);
    }

}
