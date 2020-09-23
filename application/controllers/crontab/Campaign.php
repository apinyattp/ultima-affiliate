<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function _update_jelala($updated_ids) {
        $this->load->library('jelala');
        $this->jelala->update_campaign($updated_ids);
    }

    public function update_campaign() {
        $this->_accesstrade_campaign();
        $this->admitad_campaign();
    }

    public function _accesstrade_campaign() {
        $this->load->library('accesstrade');
        $campaigns = $this->accesstrade->campaigns();

        $updated_ids = [];

        $this->load->model('campaign_model');
        foreach($campaigns as $campaign) {
            $campaign_id = $campaign['id'];

            $updated_ids[] = $campaign_id;

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

        $this->_update_jelala($updated_ids);
        
    }

    public function admitad_campaign() {

        $status = ['active' => 'APPROVED', 'pending' => 'APPLYING', 'declined' => 'REJECTED'];

        $this->load->library('admitad_api');
        $advcampaigns = $this->admitad_api->advcampaigns();

        $updated_ids = [];

        $this->load->model('campaign_model');
        foreach($advcampaigns['results'] as $campaign) {

            $campaign_id = $campaign['id'];

            $updated_ids[] = $campaign_id;

            // $campaign_detail = $this->admitad_api->advcampaign($campaign_id);

            // CHECK CAMPAIGN
            $a_campaign = $this->campaign_model->get_by_id($campaign_id);
            if(empty($a_campaign)) {
                $this->campaign_model->insert($campaign_id);
            }

            if($a_campaign['deleted']) continue;

            // UPDATE REWARD DATA
            $this->campaign_model->update_data(
                $campaign_id,
                $campaign['name'],
                'admitad',
                $campaign['site_url'],
                NULL, // type
                $campaign['activation_date'],
                NULL,
                NULL,
                NULL, // $pointBack
                $campaign['image'],
                $campaign['description'],
                $campaign['description'],
                NULL, // customCreativesAvailable
                NULL, // seoContentAvailable
                $campaign['show_products_links'],
                $campaign['allow_deeplink'], 
                $campaign['gotolink'], // quicklink
                $status[$campaign['connection_status']], // affiliateStatus
                NULL, // affiliatedDate
                $campaign['currency']
            );

            // UPDATE CATEGORY REWARD
            foreach($campaign['actions_detail'] as $action_detail) {

                foreach($action_detail['tariffs'] as $tariff) {
                    foreach($tariff['rates'] as $rate) {

                        $category_id = $action_detail['id'];
                        $type = ($rate) ? 'CPA_SALES' : 'CPA_FIXED';
                        $reward = $rate['size'];
                        $name = $action_detail['name'];

                        $this->campaign_model->update_category_reward($campaign_id, $category_id, $type, $reward, $name);

                    }
                }

            }

            $categories = [];
            foreach($campaign['categories'] as $category) {
                $categories[] = [
                    'id' => $category['id'],
                    'campaign_id' => $campaign_id,
                    'name' => $category['name'],
                    'value' => NULL,
                    'item' => NULL,
                    'parent_id' => empty($category['parent']) ? NULL : $category['parent']['id']
                ];
            }
            $this->campaign_model->update_category($campaign_id, $categories);

            if(empty($this->campaign_model->get_set_reward($campaign_id))) {
                $this->campaign_model->insert_set_reward($campaign_id);
            }  

        }

        $this->_update_jelala($updated_ids);

    }

}
