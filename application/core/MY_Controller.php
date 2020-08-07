<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends Builder\Core\Controller {

    public function __construct() {
        parent::__construct();
    }

    protected function _echo_json($code, $data = array(), $msg = FALSE) {
        $a_json = [
            'response_code' => $code,
            'response_msg' => '',
            'result' => $data,
        ];
        if($msg !== FALSE){
            $a_json['response_msg'] = $msg;
        }else{
            $a_json['response_msg'] = lang('errorcode_'.$code);
        }

        header('Content-Type: application/json');

        echo json_encode($a_json);

        return $code;
    }

    protected function _pagination_format($page, $perpage, $item_count) {
        if(empty($perpage)) {
            $_perpage = empty($item_count) ? 1 : $item_count;
            $total_page = ceil($item_count / $_perpage);
        }else{
            $total_page = ceil($item_count / $perpage);
        }
        return [
            'perpage' => $perpage,
            'page' => $page,
            'total_page' => $total_page,
            'total_items' => $item_count,
        ];
    }

    protected function _image_format($file) {
        if(!isset($file['id'])) return NULL;
        if(!isset($file['file_path'])) return NULL;
        if(!isset($file['file_data'])) return NULL;

        $data = @json_decode($file['file_data'], TRUE);
        if(empty($data)) $data = [];

        return [
            'url' => $this->_file_url($file),
            'width' => empty($data['resolutions']['width']) ? NULL : $data['resolutions']['width'],
            'height' => empty($data['resolutions']['height']) ? NULL : $data['resolutions']['height'],
        ];
    }

    protected function _file_url($file) {
        if(!isset($file['id'])) return NULL;
        if(!isset($file['file_path'])) return NULL;
        return upload_base_url().$file['file_path'];
    }

    protected function _authorization() {
        if($this->load->find_module('admin') == FALSE) return E::AUTH_MODULE_NOT_LOADED;

        $this->load->library('module/admin/authorization');

        $a_config = [
            'check_expire' => TRUE,
        ];
        $this->admin_authorization->set_config($a_config);

        $args = func_get_args();
        return call_user_func_array([$this->admin_authorization, 'authorization_check'], $args);
    }

    protected function _auth_admin() {
        $this->load->library('module/admin/authorization');
        return $this->admin_authorization->get_login_admin();
    }

    protected function _sendmail($subject, $to, $view, $data) {
        $msg = $this->load->view($view, $data, TRUE);

        return $this->_email($to, $subject, $msg);
    }

    protected function _email($to, $subject, $msg) {
        $this->load->config('email');
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $sender_name = $this->config->item('sender_name');
        $sender_email = $this->config->item('sender_email');

        $this->email->from($sender_email, $sender_name);
        $this->email->to($to);

        $this->email->subject($subject);
        $this->email->message($msg);

        if($this->email->send()) return TRUE;

        return FALSE;
    }

}
