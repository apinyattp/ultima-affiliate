<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Php_excel_object {
  protected $json_object = NULL;

  public static $KEY_page = 'page';
  public static $KEY_tab_name = 'tab_name';
  public static $KEY_end_tab_data = 'end_tab_data';
  public static $KEY_start_position = 'start_position';
  public static $KEY_keys = 'keys';
  public static $KEY_sums = 'sum_keys';
  public static $KEY_avg = 'avg_keys';
  public static $KEY_formula = 'formula_cells';

  public static $KEY_default_font_style = 'default_font_style';
  public static $KEY_default_font_color = 'default_font_color';
  public static $KEY_default_font_size = 'default_font_size';
  public static $KEY_default_cell_color = 'default_cell_color';
  public static $KEY_default_h_align = 'default_h_align';
  public static $KEY_default_v_align = 'default_v_align';
  public static $KEY_merge_cell_row_col = 'merge_cell_row_col';
  public static $KEY_color_cells = 'color_cells';
  public static $KEY_boder_cells = 'boder_cells';
  public static $KEY_align_cells = 'align_cells';
  public static $KEY_font_style = 'font_style';
  public static $KEY_header_data = 'header_data';
  public static $KEY_free_style_merge_key_range = 'free_style_merge_key_range';
  public static $KEY_data = 'data';
  public static $KEY_next_url = 'n_url';
  public static $KEY_previous_url = 'p_url';

  ///////////////////////////////////
  /////// property_value
  ///////////////////////////////////

  //position
  public static $_KEY_position = '_KEY_position';
  //cell color
  public static $_KEY_CELL_COLOR = '_KEY_CELL_COLOR';
  protected $VALUE_KEY_color_cells = array();

  //boder
  public static $_KEY_BORDER_STYLE = '_KEY_BORDER_STYLE';
  public static $_KEY_BORDER_WEIGHT = '_KEY_BORDER_WEIGHT';
  public static $_KEY_BORDER_COLOR = '_KEY_BORDER_COLOR';
  protected $VALUE_KEY_boder_cells = array();

  //align
  public static $_KEY_ALIGN_H = '_KEY_ALIGN_H';
  public static $_KEY_ALIGN_V = '_KEY_ALIGN_V';
  protected $VALUE_KEY_align_cells = array();

  //font
  public static $_KEY_FONT_STYLE = '_KEY_FONT_STYLE';
  public static $_KEY_FONT_COLOR = '_KEY_FONT_COLOR';
  public static $_KEY_FONT_SIZE = '_KEY_FONT_SIZE';
  protected $VALUE_KEY_font_style = array();

  //free style key
  public static $_KEY_FREE_STYLE_MERGE_KEY_RANGE= '_KEY_FREE_STYLE_MERGE_KEY_RANGE';
  protected $VALUE_KEY_free_style_merge_key_range = array();


  //formula key
  public static $_KEY_FORMULA_CELL= '_KEY_FORMULA_CELL';
  protected $VALUE_KEY_formula = array();

  //error
  public static $ERROR_FIELD_VALUE_KEY_NOT_CORRECT_FORMAT = 'Value key position range not correct format';
  public static $ERROR_DATA_WITH_KEY_NOT_FOUND = 'Data with key not found';
  public static $ERROR_JSON_DATA_WRONG = 'Json data wrong!';

  private $CI;
  private $BEGIN_ROW = 0;

  function __construct() {
    $this->CI = &get_instance();
    $this->CI->load->library('Php_excel/php_excel_data_helper');
    $this->VALUE_KEY_color_cells = array(Php_excel_object::$_KEY_CELL_COLOR);

    $this->VALUE_KEY_boder_cells = array(Php_excel_object::$_KEY_BORDER_STYLE,
                                        Php_excel_object::$_KEY_BORDER_WEIGHT,
                                        Php_excel_object::$_KEY_BORDER_COLOR);

    $this->VALUE_KEY_align_cells = array(Php_excel_object::$_KEY_ALIGN_H,
                                        Php_excel_object::$_KEY_ALIGN_V);

    $this->VALUE_KEY_font_style = array(Php_excel_object::$_KEY_FONT_STYLE,
                                        Php_excel_object::$_KEY_FONT_COLOR,
                                        Php_excel_object::$_KEY_FONT_SIZE);

    $this->VALUE_KEY_formula = array(Php_excel_object::$_KEY_FORMULA_CELL);

    $this->VALUE_KEY_free_style_merge_key_range = array(Php_excel_object::$_KEY_FREE_STYLE_MERGE_KEY_RANGE);
  }

  public function get_object($key = NULL, $default = NULL){

    if($key){
      if(array_key_exists($key, $this->json_object)){
        return $this->json_object[$key];
      }
      else{
        return $default;
      }
    }else{
      return $this->json_object;
    }
  }

  public function init_by_object($obj){
    try{
      $this->json_object = $obj;
    }catch(Exception $ex){
      throw new Exception(Php_excel_object::$ERROR_JSON_DATA_WRONG);
    }
  }

  public function init_by_json($data){
    try{
      $this->json_object = json_decode($data, true);
    }catch(Exception $ex){
      throw new Exception(Php_excel_object::$ERROR_JSON_DATA_WRONG);
    }
  }

  public function set_begin_row($begin_row){
    $this->BEGIN_ROW = $begin_row;
  }

  public function get_page_number(){
    if($this->get_object(Php_excel_object::$KEY_page, NULL)){
      return $this->get_object(Php_excel_object::$KEY_page, NULL);
    }else{
      throw new Exception(Php_excel_object::$ERROR_DATA_WITH_KEY_NOT_FOUND.' - '.Php_excel_object::$KEY_page);
    }
  }

  public function get_tab_name(){
    if($this->get_object(Php_excel_object::$KEY_tab_name, NULL)){
      return $this->get_object(Php_excel_object::$KEY_tab_name, NULL);
    }else{
      throw new Exception(Php_excel_object::$ERROR_DATA_WITH_KEY_NOT_FOUND.' - '.Php_excel_object::$KEY_tab_name);
    }
  }

  public function is_end_tab_data(){
    return $this->get_object(Php_excel_object::$KEY_end_tab_data, false);
  }

  public function get_next_page_url(){
    return $this->get_object(Php_excel_object::$KEY_next_url, NULL);
  }

  public function get_begin_row(){
    return $this->BEGIN_ROW;
  }

  public function get_previous_page_url(){
    return $this->get_object(Php_excel_object::$KEY_previous_url, NULL);
  }

  public function get_start_position(){
    return Php_excel_data_helper::get_excel_position($this->get_object(Php_excel_object::$KEY_start_position), $this->BEGIN_ROW);
  }

  public function get_keys(){
    return $this->get_object(Php_excel_object::$KEY_keys);
  }

  public function get_header_data(){
    $result = array();
    $keys = $this->get_keys();

    $data = $this->get_object(Php_excel_object::$KEY_header_data);
    foreach($data as $index => $data_row){
      try{
        $result_row = array();
        for($key_index = 0; $key_index < sizeof($keys) ; $key_index++){
          array_push($result_row, $data_row[$keys[$key_index]]);
        }
        array_push($result, $result_row);
      }catch(Exception $ex){
        throw new Exception(Php_excel_object::$ERROR_DATA_WITH_KEY_NOT_FOUND);
      }
    }

    return $result;
  }

  public function get_data(){
    $result = array();
    $keys = $this->get_keys();
    $data = $this->get_object(Php_excel_object::$KEY_data);
    foreach($data as $index => $data_row){
      try{
        $result_row = array();
        for($key_index = 0; $key_index < sizeof($keys) ; $key_index++){
          array_push($result_row, $data_row[$keys[$key_index]]);
        }
        array_push($result, $result_row);
      }catch(Exception $ex){
        throw new Exception(Php_excel_object::$ERROR_DATA_WITH_KEY_NOT_FOUND);
      }
    }
    return $result;
  }

  public function get_data_row_count(){
    return sizeof($this->get_object(Php_excel_object::$KEY_data, array()));
  }

  public function get_header_data_row_count(){
    return sizeof($this->get_object(Php_excel_object::$KEY_header_data));
  }

  public function get_all_row_count(){
    return $this->get_data_row_count() + $this->get_header_data_row_count();
  }

  public function get_object_boolean($key = NULL, $default = 'f'){
    if($this->get_object($key, $default) == 't'){
      return true;
    }else{
      return false;
    }
  }

  public function get_object_position_range_dict($key = NULL){
    try{
      $result = array();
      $object = $this->get_object($key, array());
      foreach($object as $index => $pos_items){
        foreach($pos_items as $item_key => $pos_item){
          $item_result = array();
          $item_result[Php_excel_object::$_KEY_position] = Php_excel_data_helper::get_excel_position_range_array($pos_item, $this->BEGIN_ROW);
          array_push($result, array_merge($this->fill_key_value($key, $item_key), $item_result));
        }
      }
      return $result;
    }catch(Exception $ex){
      throw $ex;
    }
  }

  public function get_object_position_range_array($key = NULL){
    try{
      $result = array();
      $object = $this->get_object($key, array());
      foreach($object as $item_key => $pos_item){
        $item_result = array();
        $item_result[Php_excel_object::$_KEY_position] = Php_excel_data_helper::get_excel_position_range($pos_item, $this->BEGIN_ROW);
        array_push($result, $item_result);
      }
      return $result;
    }catch(Exception $ex){
      throw $ex;
    }
  }

  public function get_object_position($key = NULL){
    try{
      $object = $this->get_object($key, "");
      return Php_excel_data_helper::get_excel_position($object, $this->BEGIN_ROW);
    }catch(Exception $ex){
      throw $ex;
    }
  }

  public function fill_key_value($key, $item_key){
    $result = array();
    $filter = NULL;
    switch($key){
      case Php_excel_object::$KEY_color_cells:
        $filter = $this->VALUE_KEY_color_cells;
        break;
      case Php_excel_object::$KEY_boder_cells:
        $filter = $this->VALUE_KEY_boder_cells;
      break;
      case Php_excel_object::$KEY_align_cells:
        $filter = $this->VALUE_KEY_align_cells;
      break;
      case Php_excel_object::$KEY_font_style:
        $filter = $this->VALUE_KEY_font_style;
      break;
      case Php_excel_object::$KEY_free_style_merge_key_range:
        $filter = $this->VALUE_KEY_free_style_merge_key_range;
      break;
      case Php_excel_object::$KEY_formula:
        $filter = $this->VALUE_KEY_formula;
      break;
    }
    $item_key_arr = explode("|", $item_key);
    if($filter){
      if(sizeof($filter) == sizeof($item_key_arr)){
        foreach($item_key_arr as $index => $value){
          $result[$filter[$index]] = $value;
        }
      }else{
        throw new Exception(Php_excel_object::$ERROR_FIELD_VALUE_KEY_NOT_CORRECT_FORMAT);
      }
    }
    return $result;
  }
}