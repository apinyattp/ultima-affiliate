<?php
if ( ! function_exists('valid_email'))
{
	/**
	 * Validate email address
	 *
	 * @deprecated	3.0.0	Use PHP's filter_var() instead
	 * @param	string	$email
	 * @return	bool
	 */
	function valid_email($email)
	{
		return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}

function valid_tel($number){
    $pattern = "/(^(\+66|0)[2689][0-9]{7,8}$)/";
    return preg_match($pattern, $number);
}

function valid_mobile($number){
    $pattern = "/(^(\+66|0)[689][0-9]{8}$)/";
    return preg_match($pattern, $number);
}

function valid_tel_mobile($number){
    return valid_tel($number) || valid_mobile($number);
}

function valid_json($a_json, $a_rule){
    foreach($a_rule as $field => $rule){
        if(array_key_exists('require', $rule)){
            if(!array_key_exists($a_json, $field)) return $rule['require'];
        }else{
            if(empty($a_json[$field])) continue ;
        }

        if(array_key_exists('string', $rule)){
            if(!is_string($a_json, $field)) return $rule['string'];

            $data = trim($a_json[$field]);
            if(array_key_exists('require', $rule) && $data === "") return $rule['string'];
        }
    }
}

function valid_zipcode($zipcode){
    $pattern = "/^[0-9]{5}$/";
    return preg_match($pattern, $zipcode);
}

function valid_date($date){
    return (bool) strtotime($date);
}

function valid_datetime($date){
    $pattern = "/^\d{4}-\d{2}-\d{2} ([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/";
    if(!preg_match($pattern, $date)) return FALSE;
    return (bool) strtotime($date);
}

function valid_birthdate($date, $time_age=0){
    return valid_date($date) && strtotime($date) < (time() - $time_age);
}

function valid_time($time){
    $pattern = "/^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/";
    return preg_match($pattern, $time);
}

function valid_time_without_sec($time){
    $pattern = "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/";
    return preg_match($pattern, $time);
}