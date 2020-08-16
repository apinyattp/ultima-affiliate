<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function update_campaign() {
        $this->load->library('accesstrade');
        $campaigns = $this->accesstrade->campaigns();

        $this->load->model('campaign_model');
        foreach($campaigns as $campaign) {

            $quicklink = $this->accesstrade->quicklink($campaign['id']);

            $default_reward = json_encode($campaign['defaultRewards']);

            $this->campaign_model->update($campaign['id'], $campaign['name'], 'accesstrade', $campaign['url'], $quicklink, $campaign['imageUrl'], $default_reward, $campaign['affiliatedDate']);
        }
        
    }

}
