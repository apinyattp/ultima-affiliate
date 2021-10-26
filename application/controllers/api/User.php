<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function generate_uuid() {
        if(($auth = $this->_api_authorization()) === FALSE) return $this->_echo_json(E::PERMISSION_DENIED);

        $uuid = $this->input->post('uuid');

        $uuid_data = $this->user_model->check_by_uuid($uuid);

        if(empty($uuid_data)) {
            $uuid_data = $this->user_model->create_user_relation($uuid, $auth);
        }

        return $this->_echo_json(E::SUCCESS, $uuid_data);
    }

}