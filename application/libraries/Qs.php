<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Query store Class
 **/

class Qs extends Qs_base{

    public function __construct() {
        parent::__construct();
    }

    public function get($table = '') {
        if ($table !== '') $this->from($table);
        $result = new Qs_result($this->ar, $this->page, $this->perpage);
        $this->reset();
        return $result;
    }

}

class Qs_result extends Qs_base {

    public function __construct($ar, $page, $perpage) {
        parent::__construct();

        $this->ar = $ar;
        $this->page = $page;
        $this->perpage = $perpage;
    }

    private function _build_query() {
        foreach ($this->ar as $data) {
            $function = $data['function'];
            $args = $data['args'];

            call_user_func_array([$this->ci->db, $function], $args);
        }
    }

    private function _limit($page=NULL, $perpage=NULL) {
        if(!$page) $page = max(1, intval($this->page));
        if(!$perpage) $perpage = min(1000, intval($this->perpage));

        if(empty($perpage)) return;

        $offset = ($page - 1) * $perpage;

        $this->ci->db->limit($perpage, $offset);
    }

    private function _count_all_results() {
        $this->_build_query();
        return $this->ci->db->count_all_results();
    }

    private function _pagination() {
        $page = max(1, intval($this->page));
        $perpage = min(1000, intval($this->perpage));
        $item_count = $this->_count_all_results();

        if(empty($perpage)) {
            $_perpage = empty($item_count) ? 1 : $item_count;
            $total_page = ceil($item_count / $_perpage);
        }else{
            $total_page = ceil($item_count / $perpage);
        }
        return [
            'perpage' => empty($perpage) ? NULL : (int) $perpage,
            'page' => (int) $page,
            'total_page' => (int) $total_page,
            'total_items' => (int) $item_count,
        ];
    }

    public function result($format=NULL, $get_all=FALSE) {
        if($get_all !== TRUE) {
            $perpage = min(1000, intval($this->perpage));
            if(empty($perpage) || $perpage < 1) throw new Exception('perpage is invalid.');
        }
        $a_item = $this->result_lists();
        if($format) $a_item = $this->ci->format->map($format, $a_item);
        return [
            'pagination' => $this->_pagination(),
            'lists' => $a_item,
        ];
    }

    public function result_lists($page=NULL, $perpage=NULL) {
        $this->_build_query();
        $this->_limit($page, $perpage);
        return $this->ci->db->get()->result_array();
    }

    public function count() {
        return $this->_count_all_results();
    }

    public function export($filename, $ext, $a_header, $format) {
        $this->ci->load->library('spout_excel');

        $this->ci->spout_excel->new_stream($filename.'.'.$ext, $ext);

        $this->ci->spout_excel->add_header($a_header);

        $item_count = $this->_count_all_results();
        $per_query = 1000;
        $total = ceil($item_count / $per_query);

        $no = 0;
        for($page = 1; $page <= $total; $page++) {
            $a_item = $this->result_lists($page, $per_query);
            foreach ($a_item as $item) {
                $no++;
                if(!array_key_exists('no', $item)) $item['no'] = $no;

                $a_row = [];
                if(is_array($format)) {
                    $a_row = $this->_export_row_format_array($item, $format);
                }elseif(is_string($format)) {
                    $a_row = $this->_export_row_format_string($item, $format);
                }
                $this->ci->spout_excel->add_row($a_row);
            }
        }

        $this->ci->spout_excel->close();
    }

    private function _export_row_format_array($item, $a_key) {
        $a_row = [];
        foreach ($a_key as $key) {
            if(array_key_exists($key, $item)) {
                $a_row[] = $item[$key];
            }else{
                $a_row[] = '';
            }
        }
        return $a_row;
    }

    private function _export_row_format_string($item, $format) {
        return $this->ci->format->run($format, $item);
    }

}

class Qs_base {
    protected $ci;

    protected $ar;

    protected $page;

    protected $perpage;

    public function __construct() {
        $this->ci = & get_instance();
        $this->reset();
    }

    public function reset() {
        $this->page = 1;
        $this->ar = [];
    }

    private function _append($function, $args) {
        $this->ar[] = [
            'function' => $function,
            'args' => $args,
        ];
    }

    public function distinct() {
        $this->_append('select', func_get_args());
        return $this;
    }

    public function select() {
        $this->_append('select', func_get_args());
        return $this;
    }

    public function select_max() {
        $this->_append('select_max', func_get_args());
        return $this;
    }

    public function select_min() {
        $this->_append('select_min', func_get_args());
        return $this;
    }

    public function select_avg() {
        $this->_append('select_avg', func_get_args());
        return $this;
    }

    public function select_sum() {
        $this->_append('select_sum', func_get_args());
        return $this;
    }

    public function from() {
        $this->_append('from', func_get_args());
        return $this;
    }

    public function join() {
        $this->_append('join', func_get_args());
        return $this;
    }

    public function where() {
        $this->_append('where', func_get_args());
        return $this;
    }

    public function or_where() {
        $this->_append('or_where', func_get_args());
        return $this;
    }

    public function where_in() {
        $this->_append('where_in', func_get_args());
        return $this;
    }

    public function or_where_in() {
        $this->_append('or_where_in', func_get_args());
        return $this;
    }

    public function where_not_in() {
        $this->_append('where_not_in', func_get_args());
        return $this;
    }

    public function or_where_not_in() {
        $this->_append('or_where_not_in', func_get_args());
        return $this;
    }

    public function like() {
        $this->_append('like', func_get_args());
        return $this;
    }

    public function or_like() {
        $this->_append('or_like', func_get_args());
        return $this;
    }

    public function not_like() {
        $this->_append('not_like', func_get_args());
        return $this;
    }

    public function or_not_like() {
        $this->_append('or_not_like', func_get_args());
        return $this;
    }

    public function group_start() {
        $this->_append('group_start', func_get_args());
        return $this;
    }

    public function or_group_start() {
        $this->_append('or_group_start', func_get_args());
        return $this;
    }

    public function not_group_start() {
        $this->_append('not_group_start', func_get_args());
        return $this;
    }

    public function or_not_group_start() {
        $this->_append('or_not_group_start', func_get_args());
        return $this;
    }

    public function group_end() {
        $this->_append('group_end', func_get_args());
        return $this;
    }

    public function group_by() {
        $this->_append('group_by', func_get_args());
        return $this;
    }

    public function having() {
        $this->_append('having', func_get_args());
        return $this;
    }

    public function or_having() {
        $this->_append('or_having', func_get_args());
        return $this;
    }

    public function order_by() {
        $this->_append('order_by', func_get_args());
        return $this;
    }

    public function page($page, $perpage=NULL) {
        $this->page = $page;
        if($perpage !== NULL) $this->perpage($perpage);
        return $this;
    }

    public function perpage($perpage) {
        $this->perpage = $perpage;
        return $this;
    }

}
