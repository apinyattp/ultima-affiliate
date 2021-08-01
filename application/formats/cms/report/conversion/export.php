<?php

$this->load->model('campaign_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);

return [
    'company' => empty($data['company']) ? 'jelala' : $data['company'],
    'conversion_id' => $data['conversion_id'],
    'campaign_name' => $a_campaign['name'],
    'uid' => $data['uid'],
    'transaction_amount' => $data['transaction_amount'],
    'reward' => $data['reward'],
    'verification_id' => $data['verification_id'],
    'click_time' => $data['click_time'],
    'conversion_time' => $data['conversion_time'],
    'confirmation_time' => $data['confirmation_time'],
    'datetime_updated' => $data['datetime_updated'],
    'status' => $data['status'],
    'missing_id' => $data['missing_id'] > 0 ? 'Yes' : 'No'
];