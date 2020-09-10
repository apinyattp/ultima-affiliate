<?php

$default_rewards = $this->campaign_model->get_default_reward($data['id']);
$category_rewards = $this->campaign_model->get_category_reward($data['id']);
// $custom_rewards = $this->campaign_model->get_custom_reward($data['id']);
$set_rewards = $this->campaign_model->get_set_reward($data['id']);

// $a_custom_rewards = [];
// foreach($custom_rewards as $custom_reward) {
//     $a_custom_rewards[$custom_reward['type']] = [];
//     foreach($custom_reward as $key => $value) {
//         if(in_array($key, ['campaign_id', 'type', 'datetime_updated'])) continue;
//         $a_custom_rewards[$custom_reward['type']][$key] = $value;
//     }
// }

$this->load->model('module/file/file_model', 'file_model');
$logo_file = $this->file_model->get_by_id($data['image_file_id']);

$logo_file_path = upload_base_url() . $logo_file['file_path'];

return [
    'id' => $data['id'],
    'display_name' => empty($data['display_name']) ? $data['name'] : $data['display_name'],
    'quick_link' => $data['quicklink'],
    'image_url' => $logo_file_path,
    'cashback' => empty($data['cashback']) ? '60 - 195' : $data['cashback'],
    'description' => $data['description'],
    'condition_do' => $data['condition_do'],
    'condition_dont' => $data['condition_dont'],
    'note' => $data['note'],
    // 'a_custom_reward' => $a_custom_rewards,
    'a_set_reward' => [
        'new' => $set_rewards['new'],
        'existing' => $set_rewards['existing']
    ],
    'coming_soon' => empty($data['coming_soon']) ? FALSE : TRUE,
    'is_pin' => empty($data['sort']) ? FALSE : TRUE,
    'pin_sort' => $data['sort'],
    'default_data' => [
        'name' => $data['name'],
        'url' => $data['url'],
        'source' => $data['source'],
        'type' => $data['type'],
        'startDate' => $data['startDate'],
        'endDate' => $data['endDate'],
        'selfConversion' => $data['selfConversion'],
        'pointBack' => $data['pointBack'],
        'englishDescription' => $data['englishDescription'],
        'customCreativesAvailable' => $data['customCreativesAvailable'],
        'seoContentAvailable' => $data['seoContentAvailable'],
        'productFeedAvailable' => $data['productFeedAvailable'],
        'quickLinkAvailable' => $data['quickLinkAvailable'],
        'affiliationStatus' => $data['affiliationStatus'],
        'affiliatedDate' => $data['affiliatedDate'],
        'currency' => $data['currency'],
        'default_reward' => $this->format->map('api/campaign/default_reward', $default_rewards),
        'category_reward' => $this->format->map('api/campaign/category_reward', $category_rewards)
    ]
];
