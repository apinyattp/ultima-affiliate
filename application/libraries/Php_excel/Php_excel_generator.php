<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Php_excel_generator
{
    public $page; //int
    public $tab_name; //String
    public $end_tab_data; //String
    public $start_position; //String
    public $keys; //array(String)
    public $sum_keys; //array(String)
    public $avg_keys; //array(String)
    public $default_font_color; //String
    public $default_font_style; //String
    public $default_font_size; //int
    public $default_cell_color; //String
    public $default_h_align; //String
    public $default_v_align; //String
    public $merge_cell_row_col; //array(String)
    public $color_cells; //array(ColorCell)
    public $boder_cells; //array(BoderCell)
    public $align_cells; //array(AlignCell)
    public $font_style; //array(FontStyle)
    public $header_data; //array(HeaderData)
    public $data; //array(Datum)
    public $n_url; //String
    public $free_style_merge_key_range;//array(Key String)
    public $formula_cells; //array(FontStyle)

    protected $font_styles_settings = array('bold', 'italic', 'normal');
    protected $h_align_settings = array('left', 'right', 'center');
    protected $v_align_settings = array('bottom', 'top', 'center');

    private function is_hex_color($color){
      if(preg_match('/^[a-f0-9]{8}$/i', $color)){
        return True;  
      }else{
        throw new Exception('Input shoud be Color Hex string');
      }
    }

    private function is_border_style($border_style){
      $border_conf_arr = explode("|", $border_style);
      if(sizeof($border_conf_arr) == 3 
        && $this->is_hex_color($border_conf_arr[2])){
          return True;  
      }else{
        throw new Exception('Input Border Style not valid');
      }
    }

    private function is_align_style($align_style){
      $align_conf_arr = explode("|", $align_style);
      if(sizeof($align_conf_arr) == 2 
        && $this->check_h_align($align_conf_arr[0])
        && $this->check_v_align($align_conf_arr[1])){
          return True;  
      }else{
        throw new Exception('Input align Style not valid');
      }
    }

    private function is_font_style($font_style){
      $font_conf_arr = explode("|", $font_style);
      if(sizeof($font_conf_arr) == 3
        && $this->check_font_style($font_conf_arr[0])
        && $this->is_hex_color($font_conf_arr[1])
        && is_numeric($font_conf_arr[2])){
          return True;  
      }else{
        throw new Exception('Input font Style cells not valid');
      }
    }

    private function is_formula_cell($formula_cell){
      $formula_cell_arr = explode("|", $formula_cell);
      if(sizeof($formula_cell_arr) == 1){
          return True;  
      }else{
        throw new Exception('Formula cells not valid');
      }
    }

    private function check_font_style($font_styles){
      $font_style_arr = explode(",", $font_styles);
      foreach($font_style_arr as $index => $font_style){
        if(in_array($font_style, $this->font_styles_settings)){
          return True;
        }else{
          throw new Exception('Font style not existing');
        }
      }
    }

    private function check_default_font_style($font_styles){
      $font_style_arr = explode("|", $font_styles);
      foreach($font_style_arr as $index => $font_style){
        if(in_array($font_style, $this->font_styles_settings)){
          return True;
        }else{
          throw new Exception('Font style not existing');
        }
      }
    }

    private function check_h_align($h_align){
      if(in_array($h_align, $this->h_align_settings)){
        return True;
      }else{
        throw new Exception('Horizontal align not existing');
      }
    }

    private function check_v_align($v_align){
      if(in_array($v_align, $this->v_align_settings)){
        return True;
      }else{
        throw new Exception('Vertical align not existing');
      }
    }

    private function to_position($posX, $posY){
      if(is_int($posX) && is_int($posY)){
        $format = '(%d,%d)';
        return sprintf($format, $posX, $posY);
      }else{
        throw new Exception('Input shoud be integer');
      }
    }

    private function to_position_pair($pos_pair_arr){//[[0,0],[1,1]]
      $pos_arr = array();
      foreach($pos_pair_arr as $index => $pos){
        array_push($pos_arr, $this->to_position($pos[0], $pos[1]));
      }
      if(sizeof($pos_arr) == 2){
        $format = '%s,%s';//(x1,y1),(x2,y2)
        return sprintf($format, $pos_arr[0], $pos_arr[1]);
      }else{
        throw new Exception('Position pair not correct');
      }
    }

    private function to_position_pair_array($pos_array){//[ [[0,0],[1,1]] , [[0,2],[2,2]] ]
      $pos_result = array();
      foreach($pos_array as $index => $pos_pair){
        array_push($pos_result, $this->to_position_pair($pos_pair));
      }
      return $pos_result;
    }

    public function getJson(){
      // return json_encode($this->color_cells);

      return json_encode($this);
    }

    protected function getPage() { 
      return $this->page ;
    }
    public function setPage($page) { 
      if(is_int($page)){
      $this->page = $page ;
      }else{
        throw new Exception('Input shoud be integer');
      }
    }

    public function getTab_name() { 
      return $this->tab_name ;
    }
    public function setTab_name($tab_name) { 
      $this->tab_name = (string) $tab_name ;
    }

    public function getEnd_tab_data() { 
      return $this->end_tab_data ;
    }
    public function set_is_end_tab_data($end_tab_data) { 
      if(is_bool($end_tab_data)){
        if($end_tab_data){
       $this->end_tab_data =  't';
        }else{
       $this->end_tab_data = 'f';
        }
      }else{
        throw new Exception('Input shoud be Boolean');
      }
    }

    public function getStart_position() { 
      return $this->start_position ;
    }
    public function setStart_position($posX, $posY) { 
      $this->start_position = $this->to_position($posX, $posY);
    }

    public function getKeys() { 
      return $this->keys ;
    }
    public function setKeys($keys) { 
      $this->keys = $keys ;
    }

    public function getSum_keys() { 
      return $this->sum_keys ;
    }
    public function setSum_keys($sum_keys) { 
      $this->sum_keys = $sum_keys ;
    }

    public function getAvg_keys() { 
      return $this->avg_keys ;
    }
    public function setAvg_keys($avg_keys) { 
      $this->avg_keys = $avg_keys ;
    }

    public function getDefault_font_color() {
      return $this->default_font_color ;
    }
    public function setDefault_font_color($default_font_color) { 
      if($this->is_hex_color($default_font_color)){
      $this->default_font_color = $default_font_color ;
      }
    }

    public function getDefault_font_style() { 
      return $this->default_font_style ;
    }
    public function setDefault_font_style($default_font_style) {
      if($this->check_default_font_style($default_font_style)){
        $this->default_font_style = $default_font_style ;
      }
    }

    public function getDefault_font_size() { 
      return $this->default_font_size ;
    }
    public function setDefault_font_size($default_font_size) { 
      if(is_numeric($default_font_size)){
        $this->default_font_size = $default_font_size ;
      }else{
        throw new Exception('Input shoud be Numeric');
      }
    }

    public function getDefault_cell_color() { 
      return $this->default_cell_color ;
    }
    public function setDefault_cell_color($default_cell_color) { 
      if($this->is_hex_color($default_cell_color)){
        $this->default_cell_color = $default_cell_color ;
      }
    }

    public function getDefault_h_align() { 
      return $this->default_h_align ;
    }
    public function setDefault_h_align($default_h_align) { 
      if($this->check_h_align($default_h_align)){
        $this->default_h_align = $default_h_align ;
      }
    }

    public function getDefault_v_align() { 
      return $this->default_v_align ;
    }
    public function setDefault_v_align($default_v_align) { 
      if($this->check_v_align($default_v_align)){
        $this->default_v_align = $default_v_align ;
      }
    }

    public function getMerge_cell_row_col() { 
      return $this->merge_cell_row_col ;
    }
    public function setMerge_cell_row_col($merge_cell_row_col) { 
      $this->merge_cell_row_col = $this->to_position_pair_array($merge_cell_row_col);
    }

    public function getColor_cells() { 
      return $this->color_cells ;
    }
    public function setColor_cells($color_cells) { 
      $this->color_cells = array();
      foreach($color_cells as $color => $cells){
        $this->addColor_cells($color, $cells);
      }
    }

    public function addColor_cells($color, $pos_pair_arr) { 
      $cell_obj = array();
      if($this->is_hex_color($color)){
        if(!$this->color_cells){
          $this->color_cells = array();
        }
        $cell_obj[$color] = $this->to_position_pair_array($pos_pair_arr);
        array_push($this->color_cells, $cell_obj);
      }
    }

    public function getBoder_cells() { 
      return $this->boder_cells ;
    }
    public function setBoder_cells($boder_cells) { 
      $this->boder_cells = array();
      foreach($boder_cells as $boder_style => $cells){
        $this->addBorder_cells($boder_style, $cells);
      }
    }

    public function addBorder_cells($border_style, $pos_pair_arr) { 
      $cell_obj = array();
      if($this->is_border_style($border_style)){
        if(!$this->boder_cells){
          $this->boder_cells = array();
        }
        $cell_obj[$border_style] = $this->to_position_pair_array($pos_pair_arr);
        array_push($this->boder_cells, $cell_obj);
      }
    }

    public function getAlign_cells() { 
      return $this->align_cells ;
    }
    public function setAlign_cells($align_cells) { 
      $this->align_cells = array();
      foreach($align_cells as $align_style => $cells){
        $this->addAlign_cells($align_style, $cells);
      }
    }

    public function addAlign_cells($align_style, $pos_pair_arr) { 
      $cell_obj = array();
      if($this->is_align_style($align_style)){
        if(!$this->align_cells){
          $this->align_cells = array();
        }
        $cell_obj[$align_style] = $this->to_position_pair_array($pos_pair_arr);
        array_push($this->align_cells, $cell_obj);
      }
    }

    public function getFont_style() { 
      return $this->font_style ;
    }
    public function setFont_style($font_styles) { 
      $this->font_style = array();
      foreach($font_styles as $font_style => $cells){
        $this->addFont_style_cells($font_style, $cells);
      }
    }

    public function addFont_style_cells($font_style, $pos_pair_arr) { 
      $cell_obj = array();
      if($this->is_font_style($font_style)){
        if(!$this->font_style){
          $this->font_style = array();
        }
        $cell_obj[$font_style] = $this->to_position_pair_array($pos_pair_arr);
        array_push($this->font_style, $cell_obj);
      }
    }


    public function getFree_style_merge_key_range() { 
      return $this->free_style_merge_key_range ;
    }
    public function setFree_style_merge_key_range($free_style_key_ranges) { 
      $this->free_style_merge_key_range = array();
      foreach($free_style_key_ranges as $key_string => $cells){
        $this->addFree_style_merge_key_range($key_string, $cells);
      }
    }

    public function addFree_style_merge_key_range($key_string, $pos_pair_arr) { 
      $cell_obj = array();
      if(!$this->free_style_merge_key_range){
        $this->free_style_merge_key_range = array();
      }
      $cell_obj[$key_string] = $this->to_position_pair_array($pos_pair_arr);
      array_push($this->free_style_merge_key_range, $cell_obj); 
    }

    public function getFormula_cell() { 
      return $this->font_style ;
    }
    public function setFormula_cell($formula_cell_arr) { 
      $this->formula_cells = array();
      foreach($formula_cell_arr as $formula_cell => $formula){
        $this->addFormula_cell($formula_cell, $formula);
      }
    }

    public function addFormula_cell($formula_cell, $formula) { 
      $cell_obj = array();
      if($this->is_formula_cell($formula_cell)){
        if(!$this->formula_cells){
          $this->formula_cells = array();
        }
        $cell_obj[$formula_cell] = $formula;
        array_push($this->formula_cells, $cell_obj);
      }
    }

    public function getHeader_data() { 
      return $this->header_data ;
    }
    public function setHeader_data($header_data) { 
      $this->header_data = $header_data ;
    }

    public function getData() { 
      return $this->data ;
    }
    public function setData($data) { 
      $this->data = $data ;
    }

    public function getN_url() { 
      return $this->n_url ;
    }
    public function setN_url($n_url) { 
      $this->n_url = $n_url ;
    }

}