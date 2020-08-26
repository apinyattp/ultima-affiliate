<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct () {
        parent::__construct();
    }

    public function list() {

    }

    public function detail() {
        // if(($auth = $this->_authorization()) !== TRUE) return $this->_echo_json($auth);
        $a_admin = $this->_auth_admin();

        $id = $this->input->get('id');

        $this->load->model('campaign_model');
        $campaign = $this->campaign_model->get_by_id($id);

        $default_rewards = $this->campaign_model->get_default_reward($id);
        $category_rewards = $this->campaign_model->get_category_reward($id);
        $custom_rewards = $this->campaign_model->get_custom_reward($id);

        $a_data = $this->format->run('api/campaign/detail', $campaign);

        return $this->_echo_json(E::SUCCESS, $a_data);
    }

}
