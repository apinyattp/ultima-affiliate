<?php

$this->load->model('campaign_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);

return [
    'id' => $data['id'],
    'conversion_id' => $data['conversion_id'],
    'campaign_id' => $data['campaign_id'],
    'a_campaign' => $a_campaign,
    'uid' => $data['uid'],
    'reward' => $data['reward'],
    'original_reward' => $data['original_reward'],
    'currency' => empty($data['currency']) ? 'THB' : $data['currency'],
    'verification_id' => $data['verification_id'],
    'click_time' => $data['click_time'],
    'conversion_time' => $data['conversion_time'],
    'confirmation_time' => $data['confirmation_time'],
    'paid_time' => $data['paid_time'],
    'datetime_updated' => $data['datetime_updated'],
    'status' => $data['status'],
];