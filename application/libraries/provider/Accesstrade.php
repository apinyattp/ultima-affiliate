<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accesstrade extends MY_Controller {

    public function __construct () {
        $this->_ci =& get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('accesstrade');
        $this->endpoint = $this->_ci->config->item('endpoint');
        $this->username = $this->_ci->config->item('username');
        $this->password = $this->_ci->config->item('password');
        $this->siteId = $this->_ci->config->item('siteId');
    }

    protected $endpoint;
    protected $username;
    protected $password;
    protected $siteId;

    private function _auth() {
        $username = $this->username;
        $password = $this->password;

        $url = $this->endpoint . 'publishers/auth/' . $username;

        $header = [
            'Authorization:' . hash('sha256', $username . ":" . md5($password))
        ];

        $response = json_decode($this->_ci->gateway->curl_json_get($url, [], $header), TRUE);

        return $response;
    }

    private function _token() {

        $auth = $this->_auth();

        $payload = [
            'sub' => $auth['userUid'],
            'iat' => time()
        ];

        $this->_ci->load->helper('jwt');
        $token = jwt_encode($payload, $auth['secretKey']);

        return $token;
    }

    private function _header() {
        $token = $this->_token();

        $header = [
            'Authorization: Bearer ' . $token,
            'X-Accesstrade-User-Type:publisher'
        ];

        return $header;
    }

    public function campaigns($endpoint=NULL) {

        $endpoint = empty($endpoint) ? 'affiliated' : $endpoint;

        $url = $this->endpoint . 'v1/publishers/me/sites/' . $this->siteId . '/campaigns/' . $endpoint;

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, [], $header), TRUE);

        return $response;
    }

    public function campaign($campaign_id) {
        $url = $this->endpoint . 'v1/campaigns/' . $campaign_id;

        $data = [
            'siteId' => $this->siteId
        ];

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);

        return $response;
    }

    public function quicklink($campaignId) {

        $url = $this->endpoint . 'v1/publishers/me/sites/'. $this->siteId .'/campaigns/'. $campaignId .'/creatives/quicklink';

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, [], $header), TRUE);

        $affiliateLink = !isset($response['affiliateLink']) ? NULL : $response['affiliateLink'];

        return $affiliateLink;
    }

    public function conversion($fromDate, $toDate, $campaignId=NULL, $conversionStatuses=NULL, $periodBase=NULL) {

        $url = $this->endpoint . 'v1/publishers/me/reports/conversion';

        $format = 'Y-m-d\T00:00:00+07:00';

        $data = [
            'siteId' => $this->siteId,
            'fromDate' => date($format, strtotime($fromDate)),
            'toDate' => date($format, strtotime($toDate)),
            'campaignId' => $campaignId,
            'periodBase' => empty($periodBase) ? 'UPDATED_DATE' : 'CONVERSION_DATE',
            'conversionStatuses' => $conversionStatuses // REJECTED|APPROVED|PENDING
        ];

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);

        return $response;
    }

    public function payment($fromMonth, $toMonth) {

        $url = $this->endpoint . 'v1/publishers/me/payment';

        $format = 'Y-m';

        $data = [
            'fromMonth' => date($format, strtotime($fromMonth)),
            'toMonth' => date($format, strtotime($toMonth)),
            'invoiceNumber' => ''
        ];

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);

        return $response;
    }

}
