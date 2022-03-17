<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function _update_jelala($updated_ids) {
        $this->load->library('jelala');
        $this->load->config('jelala');
        $a_company = $this->config->item('company');

        foreach($a_company as $company){
            $url = $company['baseurl'];
            $header = $company['header'];
            $this->jelala->update_campaign($updated_ids, $url, $header);
        }
    }

    public function update_campaign() {
        $this->_accesstrade_campaign();
        // $this->admitad_campaign();
        $this->involve_asia_campaign();
        echo 'success date time: ' .  date('d/m/Y h:i:s a', time());
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

        if(!isset($advcampaigns['results'])) return TRUE;

        $updated_ids = [];

        $this->load->model('campaign_model');
        foreach($advcampaigns['results'] as $campaign) {

            $id = sprintf("%05d", $campaign['id']);

            $campaign_code = "AMA{$id}";

            $a_campaign = $this->campaign_model->get_by_code($campaign_code);

            if(empty($a_campaign)) {
                $a_campaign = $this->campaign_model->get_by_id($campaign['id']);

                $campaign_id = !empty($a_campaign) ? $a_campaign['id'] : $this->campaign_model->insert_by_code($campaign_code);
            }else {
                $campaign_id = $a_campaign['id'];
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

    public function involve_asia_campaign() {

        $a_campaign_type = ['cpa' => 'CPA', 'cps' => 'CPS'];

        $this->load->config('affiliate/involve_asia');
        $a_offer_id = $this->config->item('a_offer_id');
        $a_offer_name = $this->config->item('a_offer_name');

        $this->load->library('involve_asia_api');
        $this->load->model('campaign_model');

        $perpage = 20;
        $page = 1;
        $total_page = 1;

        $retry = 0;
        $start = time();
        $updated_ids = [];
        for(; $page <= $total_page;){
            $result_data = $this->involve_asia_api->all_offers($page, $perpage);

            $time = time() - $start;
            echo "PAGE $page / $total_page : ${time}s ";

            if(!empty($result_data['status_code'])) {
                echo "============================================= ERROR ${result_data['status_code']}\n";
                switch($result_data['status_code']) {
                    case 429:
                        sleep(20);
                        continue 2;
                    default:
                        var_dump($result);
                        sleep(20);
                        continue 2;
                }
            }elseif(empty($result_data['data']['data'])) {
                echo "============================================= NO DATA($retry)\n";
                if($retry++ > 10) break;
                sleep(20);
                continue;
            }
            echo "============================================= SUCCESS\n";
            $retry = 0;
            if($page == 1) {
                $total_page = ceil($result_data['data']['count']/ $perpage);
            }

            foreach($result_data['data']['data'] as $result) {

                $offer_id = $result['offer_id'];
                $campaign = $result;

                $offer_id = sprintf("%05d", $campaign['offer_id']);

                $campaign_code = "IVA{$offer_id}";

                $a_campaign = $this->campaign_model->get_by_code($campaign_code);

                if(empty($a_campaign)) {
                    $campaign_id = $this->campaign_model->insert_by_code($campaign_code);
                }else {
                    $campaign_id = $a_campaign['id'];
                }

                if($a_campaign['deleted']) continue;

                $updated_ids[] = $campaign_id;

                $name = $campaign['offer_name'];
                $source = 'involve_asia';
                $url = $campaign['preview_url'];
                $type = (isset($a_campaign_type[$campaign['lookup_value']])) ? $a_campaign_type[$campaign['lookup_value']] : $campaign['lookup_value'];
                $startDate = $endDate = $selfConversion = $pointBack = NULL;
                $imageUrl = $campaign['logo'];
                $description = $englishDescription = $campaign['description'];
                $customCreativesAvailable = $seoContentAvailable = $productFeedAvailable = NULL;
                $quickLinkAvailable = TRUE;
                $quicklink = $campaign['tracking_link'];
                $affiliationStatus = 'APPROVED';
                $affiliatedDate = NULL;
                $currency = $campaign['currency'];

                $this->campaign_model->update_data(
                    $campaign_id,
                    $name,
                    $source,
                    $url,
                    $type,
                    $startDate,
                    $endDate,
                    $selfConversion,
                    $pointBack,
                    $imageUrl,
                    $description,
                    $englishDescription,
                    $customCreativesAvailable,
                    $seoContentAvailable,
                    $productFeedAvailable,
                    $quickLinkAvailable,
                    $quicklink,
                    $affiliationStatus,
                    $affiliatedDate,
                    $currency
                );


                // UPDATE CATEGORY REWARD
                foreach($campaign['commissions'] as $index => $commission) {
                    $category_id = $index;
                    $type = 'CPA_SALES';
                    $text = $commission[key($commission)];
                    $reward = $commission[key($commission)];
                    $name = key($commission);

                    $this->campaign_model->update_category_reward($campaign_id, $category_id, $type, $reward, $name, $text);
                }

                sleep(4);
                $page += 1;
            }
        }

        $this->_update_jelala($updated_ids);

    }

}
