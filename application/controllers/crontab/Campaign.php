<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function update_campaign() {
        $this->_accesstrade_campaign();
    }

    public function _accesstrade_campaign() {
        $this->load->library('accesstrade');
        $campaigns = $this->accesstrade->campaigns();

        $this->load->model('campaign_model');
        foreach($campaigns as $campaign) {
            $campaign_id = $campaign['id'];

            $quicklink = $this->accesstrade->quicklink($campaign_id);

            $campaign_detail = $this->accesstrade->campaign($campaign_id);

            // CHECK CAMPAIGN
            $a_campaign = $this->campaign_model->get_by_id($campaign_id);
            if(empty($a_campaign)) {
                $this->campaign_model->insert($campaign_id);
            }

            // UPDATE REWARD DATA
            $this->campaign_model->update_data(
                $campaign_id, 
                $campaign_detail['name'], 
                'accesstrade', 
                $campaign_detail['url'],
                $campaign_detail['type'],
                !isset($campaign_detail['startDate']) ? NULL : $campaign_detail['startDate'],
                !isset($campaign_detail['endDate']) ? NULL : $campaign_detail['endDate'],
                $campaign_detail['selfConversion'],
                $campaign_detail['pointBack'],
                $campaign['imageUrl'],
                $campaign_detail['description'],
                $campaign_detail['englishDescription'],
                $campaign['customCreativesAvailable'],
                $campaign['seoContentAvailable'],
                $campaign['productFeedAvailable'],
                $campaign['quickLinkAvailable'],
                $quicklink, $campaign_detail['affiliationStatus'],
                $campaign['affiliatedDate'],
                $campaign_detail['currency']
            );

            // UPDATE DEFAULT REWARD
            $set_default_reward = [];
            foreach($campaign_detail['defaultRewards'] as $default_reward) {
                $customerType = (!isset($default_reward['customerType'])) ? NULL : $default_reward['customerType'];
                $this->campaign_model->update_default_reward($campaign_id, $default_reward['type'], $default_reward['name'], $default_reward['reward'], $customerType);
            }

            // UPDATE CATEGORY REWARD
            foreach($campaign_detail['categoryRewards'] as $category_reward) {
                $this->campaign_model->update_category_reward(
                    $campaign_id, 
                    $category_reward['id'], 
                    $category_reward['type'], 
                    $category_reward['reward'],
                    (isset($category_reward['name'])) ? $category_reward['name'] : NULL
                );
            }

            $categories = [];
            foreach($campaign_detail['categories'] as $category) {
                $categories[] = [
                    'campaign_id' => $campaign_id,
                    'name' => $category['name'],
                    'value' => $category['value'],
                    'item' => empty($category['item']) ? NULL : json_encode($category['item'])
                ];
            }
            $this->campaign_model->update_category($campaign_id, $categories);

            if(empty($this->campaign_model->get_custom_reward($campaign_id))) {
                $this->campaign_model->insert_custom_reward($campaign_id, 'default');
                $this->campaign_model->insert_custom_reward($campaign_id, 'category');
                $this->campaign_model->insert_custom_reward($campaign_id, 'customer_type');
            }  

            if(empty($this->campaign_model->get_set_reward($campaign_id))) {
                $this->campaign_model->insert_set_reward($campaign_id);
            }  

        }
        
    }

}
