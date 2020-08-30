<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function upload() {
        $endpoint = $this->input->post('endpoint');

        $this->load->config('upload', TRUE);
        $a_config = $this->config->item('upload');

        if(!array_key_exists($endpoint, $a_config)) return $this->_echo_json(E::INVALID_FORMAT, ["field" => "endpoint"]);

        $a_rule = $a_config[$endpoint];

        if(isset($a_rule['auth'])){
            if(($auth = $this->_admin_authorization($a_rule['auth'])) !== TRUE){
                return $this->_echo_json($auth);
            }
        }

        $ext = 'gif|jpg|png|jpeg';
        if(array_key_exists('ext', $a_rule)){
            if(!is_string($a_rule['ext'])) throw new Error("Ext rule is invalid.");

            $ext = $a_rule['ext'];
        }

        $keep_name = FALSE;
        if(array_key_exists('keep_name', $a_rule)){
            if(!is_bool($a_rule['keep_name'])) throw new Error("keep_name rule is invalid.");
            $keep_name = $a_rule['keep_name'];
        }

        $max_size = NULL;
        if(array_key_exists('size', $a_rule)){
            if(!is_numeric($a_rule['size'])) throw new Error("size rule is invalid.");
            $max_size = $a_rule['size'];
        }

        $this->load->model('module/file/file_model');
        $upload_folder = \Module\File\Models\File_model::STATUS_TEMP.'/'.$endpoint;

        $result = $this->_file_upload($upload_folder, 'file', $ext, $keep_name, $max_size);

        if($result['result'] === FALSE) return $this->_echo_json(E::UPLOAD_FILE_CANNOT_NOW, ['error' => $result['error']]);

        $file_path = $upload_folder .'/'. basename($result['full_path']);

        $file_extension = preg_replace('#^\.#', '', $result['file_ext']);

        $file_type = $result['file_type'];
        $file_size = filesize($result['full_path']);

        $data = [];
        if($result['is_image']){
            $data['resolutions'] = [
                'width' => $result['image_width'],
                'height' => $result['image_height'],
            ];
        }

        $a_member = $this->_auth_admin();
        $member_id = $a_member['id'];

        $file_id = $this->file_file_model->create($member_id, $file_type, $endpoint, $file_path, $file_extension, $file_size, $data);

        $a_json = [
            'id' => $file_id,
            'url' => upload_base_url() . $file_path,
            'file_type' => $file_type,
            'file_size' => $file_size,
            'data' => $data,
        ];

        return $this->_echo_json(E::SUCCESS, $a_json);
    }

    protected function _file_upload($upload_path='', $file_var='file', $allowed_types=[], $keep_name=FALSE, $max_size=NULL) {
        mkpath($upload_path);
        $config['upload_path'] = upload_file_path().$upload_path;
        if(!empty($allowed_types)) $config['allowed_types'] = $allowed_types;
        if($keep_name === FALSE) $config['encrypt_name'] = TRUE;
        if(!empty($max_size)) $config['max_size'] = $max_size;
        $config['overwrite'] = FALSE;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if(!$this->upload->do_upload($file_var)){
            return [
                'result' => FALSE,
                'error' => implode(",\r\n", $this->upload->error_msg),
            ];
        }else{
            $data = $this->upload->data();
            $data['result'] = TRUE;
            return $data;
        }
    }

}
