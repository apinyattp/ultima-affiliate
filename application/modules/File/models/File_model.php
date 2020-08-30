<?php
namespace Module\File\Models;

class File_model extends \CI_Model {
    const STATUS_TEMP = 'temp';
    const STATUS_LIVE = 'live';
    const STATUS_DELETED = 'deleted';

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('filepath');
    }

    private $_temp_file;

    public function get_by_id($id) {
        if($this->_temp_file && $this->_temp_file['id'] == $id) return $this->_temp_file;
        $this->db->from('file')
                ->where('id',$id)
                ->limit(1);
        return $this->_temp_file = $this->db->get()->row_array();
    }

    public function get_by_ids($a_id) {
        if(!is_array($a_id)) throw new Error("Invalid argument type array");
        if(empty($a_id)) return [];
        $this->db->from('file')
                ->where_in('id', $a_id)
                ->limit(count($a_id));
        return $this->db->get()->result_array();
    }

    public function get_by_status($status) {
        $this->db->from('file')
                ->where('status', $status);
        return $this->db->get()->result_array();
    }

    public function get_by_content($content_id, $endpoint, $status=File_model::STATUS_LIVE) {
        $this->db->from('file')
                ->where('status', $status)
                ->where('content_id', $content_id)
                ->where('endpoint', $endpoint);
        return $this->db->get()->result_array();
    }

    public function verify($file_id, $endpoint, $content_id=NULL) {
        $a_file = $this->get_by_id($file_id);
        if(empty($a_file)) return FALSE;

        if($a_file['endpoint'] != $endpoint) return FALSE;
        if($a_file['content_id'] !== NULL && $a_file['content_id'] != $content_id) return FALSE;
        if($a_file['status'] === 'deleted') return FALSE;

        return TRUE;
    }

    public function create($user_id, $file_type, $endpoint, $file_path, $file_extension, $file_size, $data, $status=File_model::STATUS_TEMP) {
        $a_set = [
            'user_id' => $user_id,
            'status' => $status,
            'endpoint' => $endpoint,
            'file_type' => $file_type,
            'file_path' => $file_path,
            'file_extension' => $file_extension,
            'file_size' => $file_size,
            'file_data' => json_encode($data),
        ];

        $this->db->insert('file', $a_set);
        return $this->db->insert_id();
    }

    public function update_live($file_id, $content_id, $keep_filename=FALSE) {
        $a_file = $this->get_by_id($file_id);

        if($a_file['status'] != File_model::STATUS_TEMP) return TRUE;
        if(!file_exists(upload_file_path() . $a_file['file_path'])) return FALSE;

        $file_path_new = File_model::STATUS_LIVE.'/';
        $file_path_new .= $a_file['endpoint'].'/';

        if($keep_filename) {
            $a_file_path = explode('/', $a_file['file_path']);
            $file_name = array_pop($a_file_path);
            $file_path_new .= $a_file['id'].'/';
        }else{
            $file_name = $a_file['user_id'].'-'.$a_file['id'].'.'.$a_file['file_extension'];
        }

        mkpath($file_path_new);
        $file_path_new .= $file_name;

        if(!@rename(upload_file_path() . $a_file['file_path'], upload_file_path() . $file_path_new)) return FALSE;

        $a_set = [
            'content_id' => $content_id,
            'status' => File_model::STATUS_LIVE,
            'file_path' => $file_path_new,
        ];

        $this->db->update('file', $a_set, ['id' => $file_id]);

        $this->_temp_file = NULL;
        return TRUE;
    }

    public function delete_by_id($file_id, $unlink=TRUE) {
        if($unlink) {
            $a_file = $this->get_by_id($file_id);
            if(empty($a_file)) return;
            $file_path = upload_file_path().$a_file['file_path'];
            if(file_exists($file_path)) unlink($file_path);
        }

        $a_set = [
            'status' => File_model::STATUS_DELETED,
        ];
        $this->db->update('file', $a_set, ['id' => $file_id]);
        $this->_temp_file = NULL;
    }

}
