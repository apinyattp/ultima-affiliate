<?php

function __upload_path() {
    return 'upload/';
}

function upload_file_path() {
    return FCPATH . __upload_path();
}

function upload_base_url() {
    return base_url() . __upload_path();
}

function __asset_path() {
    return 'asset/';
}

function asset_file_path() {
    return FCPATH . __asset_path();
}

function asset_base_url() {
    return base_url() . __asset_path();
}

function GUID() {
    if (function_exists('com_create_guid') === TRUE)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function date_lang($format, $time = FALSE, $lang='th') {
    if ($time === FALSE)
        $time = time();
    switch ($lang) {
        default:
            return date($format, $time);
        case 'th':
            $thai_D = array('อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส');
            $thai_l = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
            $thai_F = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
            $thai_M = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
            $i_Y = date('Y', $time) + 543;
            $i_w = date('w', $time);
            $i_n = date('n', $time);
            $format = str_replace('D', $thai_D[$i_w], $format);
            $format = str_replace('l', $thai_l[$i_w], $format);
            $format = str_replace('F', $thai_F[$i_n], $format);
            $format = str_replace('M', $thai_M[$i_n], $format);
            $format = str_replace('Y', $i_Y, $format);
            return date($format, $time);
    }
    return '';
}

function slugify($text, $max_length=FALSE, $fallback=FALSE) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\dก-๙]+~u', '-', $text);

    // transliterate
    // $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\wก-๙]+~u', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = mb_strtolower($text);

    if (empty($text)) {
        return $fallback;
    }

    if($max_length !== FALSE){
        $text = substr($text, 0, $max_length);
    }

    return $text;
}

function find_in_array($array, $field, $value=NULL, $return_index=FALSE) {
    if(!is_array($field)){
        $a_field = array($field => $value);
    }else $a_field = $field;
    foreach($array as $index => $data){
        foreach($a_field as $field => $value){
            if($data[$field] != $value)
                continue 2;
        }
        return $return_index ? $index : $data;
    }
    return FALSE;
}
