<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gateway {
    private $_ci;
    private $_auth_user;
    private $_auth_password;
    var $_debug;

    private $_httpcode;

    public function __construct () {
        $this->_ci = &get_instance();
    }

    public function curl_set_auth($user, $password) {
        $this->_auth_user = $user;
        $this->_auth_password = $password;
    }

    public function curl($method, $url, $body_string='', $header=[]) {
        if($this->_debug === TRUE){
            echo "Method: $method<hr>\n";
            echo "URL: $url<hr>\n";
            echo "Body string: $body_string<hr>\n";
            echo "Header: <br>\n";
            var_dump($header);
        }

        $ch = curl_init();

        switch (strtoupper($method)) {
            case 'GET':
                if($this->valid_json($body_string)) {
                    $header[] = 'Content-Length: ' . strlen($body_string);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
                    break;
                }
                if(strpos($url, '?') !== FALSE){
                    $url = $url.'&'.$body_string;
                }else{
                    $url = $url.'?'.$body_string;
                }
                break;
            case 'POST':
                $header[] = 'Content-Length: ' . strlen($body_string);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
                break;
            case 'DELETE':
                if($this->valid_json($body_string)) {
                    $header[] = 'Content-Length: ' . strlen($body_string);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
                    break;
                }
                if(strpos($url, '?') !== FALSE){
                    $url = $url.'&'.$body_string;
                }else{
                    $url = $url.'?'.$body_string;
                }
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        if($this->_auth_user && $this->_auth_password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->_auth_user . ":" . $this->_auth_password);
        }

        $msg_error = '';
        try{
            $result = curl_exec($ch);
        } catch(Exception $ex){
            $msg_error = $ex->getMessage();
            $result = FALSE;
        }

        if($result === FALSE) {
            if(empty($msg_error)) $msg_error = curl_error($ch);
            log_message('error', 'Gateway:CODE('.curl_errno($ch).')|'.$msg_error.'|'.$method.'|'.$url.'|'.$body_string);
        }

        if($this->_debug === TRUE){
            echo "<hr>RESULT: $result<br>\n";
            if($msg_error) echo "<hr>ERROR: $msg_error<br>\n";
            echo "<hr>INFO:";
            var_dump(curl_getinfo($ch));
            echo "<hr>";
        }

        $this->_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $result;
    }

    public function curl_get($url, $data=[], $header=[]) {
        $body_string = http_build_query($data);

        return $this->curl('GET', $url, $body_string, $header);
    }

    public function curl_json($url, $data=[], $header=[]) {
        $body_string = json_encode($data);

        $header[] = 'Content-Type: application/json';

        return $this->curl('POST', $url, $body_string, $header);
    }

    public function curl_post($url, $data=[], $header=[]) {
        $body_string = http_build_query($data);
        return $this->curl('POST', $url, $body_string, $header);
    }

    public function curl_json_get($url, $data=[], $header=[]) {
        $body_string = json_encode($data);
        $header[] = 'Content-Type: application/json';

        return $this->curl('GET', $url, $body_string, $header);
    }

    public function curl_delete($url, $data=[], $header=[]) {
        $body_string = json_encode($data);
        $header[] = 'Content-Type: application/json';

        return $this->curl('DELETE', $url, $body_string, $header);
    }

    private function valid_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function _httpcode() {
        return $this->_httpcode;
    }
}
