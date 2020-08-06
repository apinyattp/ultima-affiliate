<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Document;

class Document {
    private $_ci;
    private $_builder;
    private $_path;
    private $_a_data = [];

    public function __construct($builder, $a_data) {
        $this->set_builder($builder);
        $this->set_data($a_data);
        $this->_ci = & get_instance();
    }

    public function set_builder($builder) {
        $this->_builder = $builder;
    }

    public function set_data($a_data) {
        $this->_a_data = $a_data;
    }

    public function render($file) {
        list($file, $type) = $this->_file($file);
        if(!empty($file)) {
            if(!isset($this->_a_data[$type][$file])) return show_404();
        }else{
            $a_key = array_keys($this->_a_data[$type]);
            sort($a_key);
            $file = array_shift($a_key);
            if(empty($file)) return show_404();
        }

        $this->_ci->doc_head->js_add('document/js/explorer.js');

        $menu = $this->_gen_menu($type);

        $this->_ci->load->view("document/template/header", ['a_data' => $menu, 'file' => $file, 'type' => $type]);
        $this->_ci->load->view("document/main", ['a_data' => $this->_a_data, 'file' => $file, 'type' => $type]);
        $this->_ci->load->view("document/template/footer", ['a_data' => $menu, 'file' => $file, 'type' => $type]);
    }

    protected function _gen_menu($type) {
        $menu = [];
        foreach ($this->_a_data[$type] as $file => $data) {
            $file = trim($file, '/');
            $a_segment = explode('/', $file);

            $menu = $this->_menu($type, $menu, $a_segment, $data);
        }
        return $menu;
    }

    protected function _menu($type, $menu, $a_segment, $data) {
        $segment = array_shift($a_segment);
        if(!isset($menu[$segment])) $menu[$segment] = [];
        if(empty($a_segment)){
            $menu[$segment]['__file'] = $data;
        }else{
            $menu[$segment] = $this->_menu($type, $menu[$segment], $a_segment, $data);
        }
        return $menu;
    }

    protected function _file($file) {
        $file = preg_replace("/^document/", "", $file);
        if(empty($file)) return ['', 'api'];
        $type = $this->_type($file);
        $file = preg_replace("/^\/{$type}/", "", $file);
        return [$file, $type];
    }

    protected function _type($file) {
        if(preg_match('/^(\/)?sitemap/', $file)) return 'sitemap';
        return 'api';
    }

}
