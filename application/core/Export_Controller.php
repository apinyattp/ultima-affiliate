<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    private $_export_url = FALSE;

    public function _echo_json($code, $data = [], $msg = FALSE, $a_config=[]) {
        if($this->_export_url) {
            if($this->input->is_export_data()){
                if($code != 200){
                    $msg = empty($msg) ? lang('errorcode_'.$code) : $msg;
                    throw new Exception($msg);
                }
                return $this->___export_data($data, $a_config);
            }
        }
        return parent::_echo_json($code, $data, $msg);
    }

    protected function _export($export_url, $file_name=FALSE) {
        $this->_export_url = $export_url;
        if($this->input->json('export_url')){
            $this->_echo_json(E::SUCCESS, $this->_get_export_url());
            exit;
        }
        if(!$this->input->is_export()) return;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$file_name.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        if(empty($file_name)) $filename = basename($export_url);

        $this->load->library('php_excel');

        $this->php_excel->gen_excel_with_url($this->___get_url(), 'php://output');
        // $this->php_excel->gen_excel_with_url($this->___get_url(), upload_file_path().$file_name.'.xlsx');
        exit;
    }

    private function ___export_data($data, $a_config) {
        $this->load->library('php_excel');

        $page = $data['pagination']['page'];
        $total_page = $data['pagination']['total_page'];

        if(empty($data['lists'])){
            $_text_datanotfound = "Data not found";
            $a_key = [$_text_datanotfound];
            $a_header = [$_text_datanotfound => $_text_datanotfound];
            $a_data = [[$_text_datanotfound => ""]];
        }else{
            $_data = $data['lists'][0];

            $a_key = array_keys($_data);
            $a_header = array_reduce(
                $a_key,
                function ($a_header, $key) {
                    $a_header[$key] = $key;
                    return $a_header;
                }, []
            );

            $a_data = array_map([$this, '___data_format'], $data['lists']);
        }

        $a_config_default = [
            "page" => $page,
            "tab_name" => "Export data",
            "end_tab_data" => "t",
            "start_position" => "(0,0)",
            "keys" => $a_key,
            "sum_keys" => [],
            "avg_keys" => [],
            "default_font_color" => "FF000000",
            "default_font_style" => "normal",
            "default_font_size" => 10,
            "default_cell_color" => "FFFFFFFF",
            "default_h_align" => "left",
            "default_v_align" => "center",
            "merge_cell_row_col" => [],
            "color_cells" => [],
            "boder_cells" => [],
            "align_cells" => [],
            "font_style" => [],
            "header_data" => [$a_header],
            "data" => $a_data,
            "n_url" => $this->___get_next_url($page, $total_page),
        ];

        foreach($a_config as $config_name => $value){
            $a_config_default[$config_name] = $value;
        }

        echo json_encode($a_config_default);
    }

    private function ___get_next_url($page, $total_page) {
        if($page >= $total_page) return NULL;

        return $this->___get_url($page + 1);
    }

    private function ___get_url($page=NULL) {
        $a_param = $this->input->json();
        unset($a_param['export']);
        unset($a_param['export_url']);
        $a_param['__export_data__'] = 1;
        $a_param['perpage'] = 100;
        $a_param['Authorization'] = $this->_authorization_token();
        if(!empty($page)) $a_param['page'] = $page;

        return $this->_export_url . (strpos($this->_export_url, '?') === FALSE ? '?' : '&'). 'json_param='. urlencode(json_encode($a_param));
    }

    protected function _get_export_url() {
        $a_param = $this->input->json();
        unset($a_param['export_url']);
        $a_param['export'] = 1;
        $a_param['Authorization'] = $this->_authorization_token();
        $a_param['page'] = 1;

        return $this->_export_url . (strpos($this->_export_url, '?') === FALSE ? '?' : '&'). 'json_param='. urlencode(json_encode($a_param));
    }

    private function ___data_format($data) {
        foreach($data as &$value){
            if(is_array($value)) $value = $this->___array_to_str($value);
        }
        return $data;
    }

    public function ___array_to_str($array, $level=0) {
        foreach($array as &$data){
            if(is_array($data)) $data = $this->___array_to_str($data, $level + 1);
        }
        $str = implode($array);
        if($level > 0) $str = '['.$str.']';
        return $str;
    }

}
