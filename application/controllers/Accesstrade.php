<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accesstrade extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('gateway');

        $this->load->config('accesstrade');
        $this->endpoint = $this->config->item('endpoint');
        $this->username = $this->config->item('username');
        $this->password = $this->config->item('password');
        $this->siteId = $this->config->item('siteId');
    }

    protected $endpoint;
    protected $username;
    protected $password;
    protected $siteId;

    private function _auth()
    {
        $username = $this->username;
        $password = $this->password;

        $url = $this->endpoint . 'publishers/auth/' . $username;

        $header = [
            'Authorization:' . hash('sha256', $username . ":" . md5($password))
        ];

        $response = json_decode($this->gateway->curl_json_get($url, [], $header), true);

        return $response;
    }

    private function _token()
    {
        $auth = $this->_auth();

        $payload = [
            'sub' => $auth['userUid'],
            'iat' => time()
        ];

        $this->load->helper('jwt');
        $token = jwt_encode($payload, $auth['secretKey']);

        return $token;
    }

    private function _header()
    {
        $token = $this->_token();

        $header = [
            'Authorization: Bearer ' . $token,
            'X-Accesstrade-User-Type:publisher'
        ];

        return $header;
    }

    public function gen_token()
    {
        echo $this->_token();
    }

    public function sites()
    {
        $url = $this->endpoint . 'v1/publishers/me/sites';

        $header = $this->_header();

        $response = json_decode($this->gateway->curl_get($url, [], $header), true);

        $this->_echo_json(E::SUCCESS, $response);
    }

    public function campaigns()
    {
        $endpoint = $this->input->get('endpoint'); // affiliated, applied, rejected, unaffiliated

        $endpoint = empty($endpoint) ? 'affiliated' : $endpoint;

        $url = $this->endpoint . 'v1/publishers/me/sites/32841/campaigns/' . $endpoint;

        $header = $this->_header();
        $response = json_decode($this->gateway->curl_get($url, [], $header), true);

        $this->_echo_json(E::SUCCESS, $response);
    }

    public function campaign($id)
    {
        $url = $this->endpoint . 'v1/campaigns/' . $id;

        $data = [
            'siteId' => '32841'
        ];

        $header = $this->_header();
        $response = json_decode($this->gateway->curl_get($url, $data, $header), true);

        $this->_echo_json(E::SUCCESS, $response);
    }

    public function campaign_affiliate()
    {
        $url = $this->endpoint . 'v1/campaigns/affiliate';

        $data = [
            'siteId' => '32841',
            'campaignIds' => [
                // Office Mate : affiliated
                137,
                // Shopee : applied
                // 534,
                // Pomelo : unaffiliated
                // 596
            ]
        ];

        $header = $this->_header();
        $response = json_decode($this->gateway->curl_json($url, $data, $header), true);

        $this->_echo_json(E::SUCCESS, $response);
    }

    public function conversion()
    {
        $fromDate = $this->input->get('fromDate');
        $toDate = $this->input->get('toDate');
        $campaignId = $this->input->get('campaignId');
        $conversionStatuses = $this->input->get('conversionStatuses');
        $periodBase = $this->input->get('periodBase');

        $url = $this->endpoint . 'v1/publishers/me/reports/conversion';

        $format = 'Y-m-d\T00:00:00+07:00';

        $data = [
            'siteId' => $this->siteId,
            'fromDate' => date($format, strtotime($fromDate)),
            'toDate' => date($format, strtotime($toDate)),
            'campaignId' => $campaignId,
            'periodBase' => empty($periodBase) ? 'CONVERSION_DATE' : 'UPDATED_DATE',
            'conversionStatuses' => $conversionStatuses
        ];

        $header = $this->_header();
        $response = json_decode($this->gateway->curl_get($url, $data, $header), true);

        $this->_echo_json(E::SUCCESS, $response);
    }

    public function quicklink()
    {
        $uid = $this->input->get('uid');
        $campaignId = $this->input->get('campaignId');

        $campaignId = empty($campaignId) ? 534 : $campaignId;

        $url = $this->endpoint . 'v1/publishers/me/sites/'. $this->siteId .'/campaigns/'. $campaignId .'/creatives/quicklink';

        $body_string = http_build_query(['uid' => $uid]);

        $header = $this->_header();
        $response = json_decode($this->gateway->curl_get($url, [], $header), true);

        $myurl = $response['affiliateLink'] . '?' . $body_string;


        $this->_echo_json(E::SUCCESS, ['affiliateLink' => $myurl]);
    }

}
