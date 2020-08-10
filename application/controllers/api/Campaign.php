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

        $url = 'https://prf.hn/click/camref:1101l4Qz9/pubref:{clickid}/adref:{psn}/destination:https://shopee.co.th/universal-link/?uid=27&name=kkkk7';

        return $this->_echo_json(E::SUCCESS, ['url' => $url]);
    }

}
