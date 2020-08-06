<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Php_excel_data_helper {

  //error
  public static $ERROR_INPUT_POSITION_TUPLE_WRONG = 'Position range input wrong';

  public static function convert_program_position_to_excel_positon($position){// 1 => A
    $c = intval($position) + 1;
    if ($c <= 0) 
      return '';
    $letter = '';
            
    while($c != 0){
      $p = ($c - 1) % 26;
      $c = intval(($c - $p) / 26);
      $letter = chr(65 + $p) . $letter;
    }
    return $letter;
  }

  public static function get_excel_position($position, $begin_row){ // A1
    $replace_from_keys = array("(", ")");
    $replace_to_value = array("", "");
    $position = str_replace($replace_from_keys, $replace_to_value, $position);
    $position_tuple = explode(",", $position);
    $row = $position_tuple[0]+1+$begin_row;
    $col = Php_excel_data_helper::convert_program_position_to_excel_positon($position_tuple[1]);
    return $col.$row;
  }

  public static function get_excel_position_range($position, $begin_row){// A1:A2
    $replace_from_keys = array("(", ")");
    $replace_to_value = array("", "");
    $result = array();
    $position_tuple = explode("),", $position);
    if(sizeof($position_tuple) == 2){
      foreach($position_tuple as $key => $pos){
        array_push($result, Php_excel_data_helper::get_excel_position($pos, $begin_row));
      }
    }else{
      throw new Exception(Php_excel_data_helper::$ERROR_INPUT_POSITION_TUPLE_WRONG);
    }
    
    return $result;
  }

  public static function get_excel_position_range_array($position_array, $begin_row){// A1:A2, B1:B2
    $result = array();
    foreach($position_array as $key => $position){
      array_push($result, Php_excel_data_helper::get_excel_position_range($position, $begin_row));
    }
    return $result;
  }
}