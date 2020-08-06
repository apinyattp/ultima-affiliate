<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected function _slug_create($name, $table, $id=NULL) {
        $retry_max = 100;
        for($retry = 1; $retry <= $retry_max; $retry++){
            $slug = slugify($name);
            if($retry != 1) $slug .= '-'.$retry;

            $this->db->select('id, slug')
                    ->from($table)
                    ->where('slug', $slug)
                    ->limit(1);
            $a_slug = $this->db->get()->row_array();
            if(empty($a_slug) || $a_slug['id'] === $id) return $slug;
        }

        return FALSE;
    }

    protected function _insert_ignore($table, $data) {
        $insert_str = $this->db->insert_string($table, $data);
        $insert_str = preg_replace("#^INSERT#", "INSERT IGNORE", $insert_str);
        $this->db->query($insert_str);
        return $this->db->affected_rows() > 0;
    }

}
