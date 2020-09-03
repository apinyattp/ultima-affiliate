<?php

$this->load->model('campaign_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);

$products = json_decode($data['products'], TRUE);

foreach($products as $key => $product) {
    $category_reward = $this->campaign_model->get_category_reward_by_id($product['categoryId']);

    $products[$key]['category'] = $this->format->run('api/report/campaign_category_reward', $category_reward);
}

return [
    'id' => $data['id'],
    'conversion_id' => $data['conversion_id'],
    'a_campaign' => $this->format->run('api/report/campaign', $a_campaign),
    'uid' => $data['uid'],
    'reward' => $data['reward'],
    'click_time' => $data['click_time'],
    'conversion_time' => $data['conversion_time'],
    'confirmation_time' => $data['confirmation_time'],
    'datetime_updated' => $data['datetime_updated'],
    'status' => $data['status'],
    'transaction_id' => $data['verification_id'],
    'products' => $products
];