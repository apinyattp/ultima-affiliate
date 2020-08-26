<?php

$default_rewards = $this->campaign_model->get_default_reward($data['id']);
$category_rewards = $this->campaign_model->get_category_reward($data['id']);
$custom_rewards = $this->campaign_model->get_custom_reward($data['id']);

$set_custom_rewards = [];
foreach($custom_rewards as $custom_reward) {
    $set_custom_rewards[$custom_reward['type']] = [];
    foreach($custom_reward as $key => $value) {
        if(in_array($key, ['campaign_id', 'type', 'datetime_updated'])) continue;
        $set_custom_rewards[$custom_reward['type']][$key] = $value;
    }
}

return [
    'id' => $data['id'],
    'display_name' => empty($data['display_name']) ? $data['name'] : $data['display_name'],
    'cashback' => $data['cashback'],
    'description' => $data['description'],
    'condition_do' => $data['condition_do'],
    'condition_dont' => $data['condition_dont'],
    'note' => $data['note'],
    'quicklink' => $data['quicklink'],
    'startDate' => $data['startDate'],
    'endDate' => $data['endDate'],
    'imageUrl' => $data['imageUrl'],
    'default_rewards' => $default_rewards,
    'category_rewards' => $category_rewards,
    'a_custom_reward' => $set_custom_rewards
];
