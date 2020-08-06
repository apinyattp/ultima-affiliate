<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Input;

class Input {
    const DATANOTSET = "========== DATA NOT SET ============";
    private $_var_name;
    private $_validator;
    private $_value;
    private $_method;

    public function __construct($var_name, $validate, $value=NULL) {
        $this->_ci = &get_instance();
        $this->_var_name = $var_name;
        $this->_validate = $validate;
        $this->_value = $value;
        $this->_method = "LOCAL";
    }

    public function var_name() {
        return $this->_var_name;
    }

    public function validate_string() {
        return $this->_validate;
    }

    public function get_input_type() {
        $a_validate = explode('|', $this->_validate);
        if(in_array('file', $a_validate)){
            return 'file';
        }
        return 'textbox';
    }

    public function is_required() {
        $a_validate = explode('|', $this->_validate);
        return in_array('required', $a_validate);
    }

    public function json_data() {
        return $this->_json_data($this->_validate);
    }

    protected function _json_data($validate, $a_json=[]) {
        $a_validate = explode('|', $validate);
        foreach($a_validate as $validate){
            if(!$this->_validate_validate($validate, $match)) throw new \Exception("Invalid validator: {$var_name}: {$validate}");
            $data_type = $match[1];
            switch (strtolower($data_type)) {
                case 'array':
                    $sub_data_type = empty($match[3]) ? NULL : $match[3];
                    if(empty($sub_data_type)) continue 2;

                    $a_json = $this->_json_data($sub_data_type, $a_json);
                    break;
                case 'object':
                case 'json':
                    $json_file = empty($match[3]) ? NULL : $match[3];
                    if(empty($json_file)) continue 2;
                    if(isset($a_json[$json_file])) continue 2;

                    $a_json[$json_file] = get_instance()->load->json($json_file);

                    foreach($a_json[$json_file] as $key => $validate) {
                        $a_json = $this->_json_data($validate, $a_json);
                    }

                    break;
            }
        }
        return $a_json;
    }

    public function validate() {
        return $this->_validate($this->_var_name, $this->_validate);
    }

    protected function _validate($var_name, $validate, $value=Input::DATANOTSET) {
        $this->_ci->load->helper('validate');
        $a_validate = explode('|', $validate);

        $_datanotset = FALSE;
        if($value === Input::DATANOTSET) {
            $_datanotset = TRUE;
            $value = $this->_get_value($a_validate);
        }

        if(($pos = array_search('trim', $a_validate)) !== FALSE) {
            if(is_string($value)) {
                $value = trim($value);
                if($_datanotset === TRUE) $this->value_set($this->_method, $value);
            }
            array_splice($a_validate, $pos, 1);
        }

        if(($pos = array_search('required', $a_validate)) !== FALSE) {
            if(is_array($value) && empty($value)) return new Response(\E::REQUIRE_PARAMETER, ['field' => $var_name]);
            if(is_null($value) || $value === '') return new Response(\E::REQUIRE_PARAMETER, ['field' => $var_name]);
            array_splice($a_validate, $pos, 1);
        }elseif(is_null($value) || $value === '') {
            return TRUE;
        }

        $result = $this->_validate_data_type($var_name, $value, $a_validate);

        if($result !== TRUE) {
            return $result[0];
        }

        $result = $this->_validate_data_format($var_name, $value, $a_validate);

        if($result !== TRUE) {
            return $result;
        }

        return TRUE;
    }

    public function value() {
        try{
            $value = $this->_get_value_by_method($this->_method);
        }catch(\Exception $e) {
            $value = $this->_value;
        }
        return $value;
    }

    protected function _get_value($a_validate) {
        if(!is_null($this->_value)) return $this->_value;

        $value = NULL;
        if(($pos = array_search('file', $a_validate)) !== FALSE) {
            $value = $this->_ci->input->arrays($_FILES, $this->_var_name);
            if($value !== NULL) {
                $this->_method = 'file';
                return $value;
            }
        }
        $a_method = $this->_find_method($a_validate);

        foreach($a_method as $method) {
            $value = $this->_get_value_by_method($method);
            if($value !== NULL) {
                $this->_method = $method;
                break;
            }
        }

        return $value;
    }

    protected function _validate_validate($validate, &$match=[]) {
        return preg_match("/^(.+?)(\.(.+)|\((.+)\))?$/", $validate, $match);
    }

    protected function _find_method(&$a_validate) {
        $a_method = [];
        foreach($a_validate as $i => $validate) {
            switch (strtoupper($validate)) {
                case 'GET':
                case 'PUT':
                case 'POST':
                case 'JSON':
                case 'GET_POST':
                case 'POST_GET':
                    $a_method[] = $validate;
                    unset($a_validate[$i]);
                    break;
            }
        }
        $a_method[] = strtoupper($this->_ci->input->method());
        $a_validate = array_values($a_validate);
        return $a_method;
    }

    protected function _get_value_by_method($method) {
        switch (strtoupper($method)) {
            case 'GET':
                return $this->_ci->input->get($this->_var_name);
            case 'PUT':
            case 'POST':
                return $this->_ci->input->post($this->_var_name);
            case 'JSON':
                return $this->_ci->input->json($this->_var_name);
            case 'GET_POST':
                return $this->_ci->input->get_post($this->_var_name);
            case 'POST_GET':
                return $this->_ci->input->post_get($this->_var_name);
            case 'LOCAL':
                return $this->_value;
        }
        throw new \Exception("Unknown method: {$this->_var_name}: {$method}");
    }

    public function value_set($method, $value) {
        switch (strtoupper($method)) {
            case 'GET':
            case 'GET_POST':
                $method = 'get';
                break;
            case 'PUT':
            case 'POST':
            case 'POST_GET':
                $method = 'post';
                break;
            case 'JSON':
                $method = 'json';
                break;
            case 'LOCAL':
                return $this->_value = $value;
                break;
            default:
                throw new \Exception("Unknown method value_set: {$this->_var_name}: {$method}");
        }
        return $this->_ci->input->input_set($method, $this->_var_name, $value);
    }

    public function _validate_data_type($var_name, $value, &$a_validate) {
        $pass = FALSE;
        $a_error = [];

        foreach ($a_validate as $i => $validate) {
            $result = $this->_validate_data_type_switch($var_name, $value, $validate);
            if($result === FALSE) continue;

            unset($a_validate[$i]);

            if($result === TRUE) {
                $pass = TRUE;
                continue;
            }

            $a_error[] = $result;
        }
        $a_validate = array_values($a_validate);
        return $pass || empty($a_error) ? TRUE : $a_error;
    }

    public function _validate_data_type_switch($var_name, $value, $validate) {
        if(!$this->_validate_validate($validate, $match)) throw new \Exception("Invalid validator: {$var_name}: {$validate}");
        $data_type = $match[1];
        switch (strtolower($data_type)) {
            case 'string':
                if(is_string($value) || is_numeric($value)) return TRUE;

                $error = new Response(\E::INVALID_DATA_TYPE_STRING, ['field' => $var_name]);
                break;
            case 'int':
                if(is_numeric($value) && $value == intval($value)) return TRUE;

                $error = new Response(\E::INVALID_DATA_TYPE_INT, ['field' => $var_name]);
                break;
            case 'float':
            case 'double':
                if(is_numeric($value) && $value == floatval($value))return TRUE;

                $error = new Response(\E::INVALID_DATA_TYPE_FLOAT, ['field' => $var_name]);
                break;
            case 'boolean':
                if(is_bool($value)) return TRUE;
                if(is_numeric($value) && in_array($value, [0, 1])) return TRUE;

                $error = new Response(\E::INVALID_DATA_TYPE_BOOLEAN, ['field' => $var_name]);
                break;
            case 'array':
                $sub_data_type = empty($match[3]) ? NULL : $match[3];
                $result = $this->_validate_data_type_array($var_name, $value, $sub_data_type);

                if($result === FALSE) return FALSE;
                if($result === TRUE) return TRUE;

                $error = $result;
                break;
            case 'object':
            case 'json':
                $json_file = empty($match[3]) ? NULL : $match[3];
                $result = $this->_validate_data_type_object($var_name, $value, $json_file);

                if($result === FALSE) return FALSE;
                if($result === TRUE) return TRUE;

                $error = $result;
                break;
            case 'file':
                if(!empty($value['tmp_name']) && is_uploaded_file($value['tmp_name'])) return TRUE;

                $error = new Response(\E::INVALID_DATA_TYPE_FILE, ['field' => $var_name]);
                break;
            default:
                return FALSE;
                // throw new \Exception("Unknown validator: {$var_name}: {$data_type}");
        }
        return $error;
    }

    public function _validate_data_type_array($var_name, $value, $sub_data_type=NULL) {
        if(!is_array($value)) return new Response(\E::INVALID_DATA_TYPE_ARRAY, ['field' => $var_name]);

        if(empty($sub_data_type)) return TRUE;

        $i = 0;
        foreach($value as $_value) {
            $result = $this->_validate_data_type_switch($var_name.'['.$i.']', $_value, $sub_data_type);
            if($result !== TRUE) return $result;
            $i ++;
        }

        return TRUE;
    }

    public function _validate_data_type_object($var_name, $value, $json=NULL) {
        if(!is_array($value)) return new Response(\E::INVALID_DATA_TYPE_OBJECT, ['field' => $var_name]);

        if(empty($json)) return TRUE;

        $a_json = get_instance()->load->json($json);

        foreach($a_json as $key => $validate) {
            $key_name = $var_name.'['.$key.']';

            $_value = $this->_ci->input->arrays($value, $key);

            $result = $this->_validate($key_name, $validate, $_value);
            if($result !== TRUE) return $result;
        }

        return TRUE;
    }

    public function _validate_data_format($var_name, $value, &$a_validate) {
        foreach ($a_validate as $i => $validate) {
            $result = $this->_validate_data_format_switch($var_name, $value, $validate);

            if($result !== TRUE) return $result;
        }

        return TRUE;
    }

    public function _validate_data_format_switch($var_name, $value, $validate) {
        if(!$this->_validate_validate($validate, $match)) throw new \Exception("Invalid validator: {$var_name}: {$validate}");
        $data_format = strtolower($match[1]);

        switch ($data_format) {
            case 'enum':
                $choice_str = empty($match[4]) ? NULL : $match[4];
                if($choice_str === NULL) throw new \Exception("Invalid validator: {$var_name}: {$data_type}");

                $a_choice = explode(';', $choice_str);

                if(in_array($value, $a_choice)) return TRUE;

                $error = new Response(\E::INVALID_FORMAT, ['field' => $var_name, 'available' => $a_choice]);
                break;

            default:
                $func_name = '\valid_'.$data_format;
                if(!function_exists($func_name)) throw new \Exception("Unknown validator: {$var_name}: {$data_format}");
                if($func_name($value)) return TRUE;

                $error = new Response(\E::INVALID_FORMAT, ['field' => $var_name, 'format' => $data_format]);
                break;
        }

        return $error;
    }

}
