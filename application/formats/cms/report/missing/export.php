<?php

$this->load->model('campaign_model');
$this->load->model('user_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);
$a_users = $this->user_model->check_by_uuid($data['uuid']);

return [
    'company' => empty($a_users) ? 'jelala' : $a_users['company'],
    'campaign_name' => $a_campaign['name'],
    'uid' => $data['uuid'],
    'amount' => $data['amount'],
    'order_id' => $data['order_id'],
    'order_date' => $data['order_date'],
    'datetime_updated' => $data['datetime_updated']
];