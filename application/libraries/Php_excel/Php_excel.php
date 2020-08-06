<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once VENDORPATH.'autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Php_excel {
    private $CI;
    private $php_excel_obj = NULL;
    private $spreadsheet = NULL;
    private $tab_name_row_count_dict = [];
    private $source_url = '';
    private $current_url = '';
    private $file_path = '';
    private $sheet_count = 0;
    private $_new_sheet = FALSE;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('curl');
        $this->CI->load->library('Php_excel/php_excel_object');
        $this->CI->load->library('Php_excel/php_excel_generator');
        $this->spreadsheet = new Spreadsheet();

    }

    public function gen_excel_with_php_excel_object($object, $file_path=NULL) {
        if(!is_null($file_path)) $this->file_path = $file_path;
        $this->do_gen_excel_with_data($object);
    }

    public function gen_excel_with_json_data($data, $file_path=NULL) {
        if(!is_null($file_path)) $this->file_path = $file_path;
        $this->do_gen_excel_with_data($data);
    }

    public function gen_excel_with_url($url, $file_path=NULL) {
        $this->source_url = $url;
        $this->current_url = $url;
        $this->CI->curl->ssl(FALSE, FALSE);
        $this->do_gen_excel_with_data($this->CI->curl->simple_get($this->current_url), $file_path);
    }

    private function get_current_sheet() {
        return $this->spreadsheet->getSheet($this->sheet_count - 1);
    }

    private function get_begin_row() {
        try{
            $tab_name = $this->php_excel_obj->get_tab_name();
            if(array_key_exists($tab_name, $this->tab_name_row_count_dict)) {
                return $this->tab_name_row_count_dict[$tab_name];
            }else{
                return 0;
            }
        }catch(Exception $ex) {
            return 0;
        }
    }

    private function update_key_tabname_with_row_count_to_dict($row=NULL) {
        $tab_name = $this->php_excel_obj->get_tab_name();
        if($row === NULL) $row = $this->php_excel_obj->get_all_row_count();
        if(array_key_exists($tab_name, $this->tab_name_row_count_dict)) {
            $this->tab_name_row_count_dict[$tab_name] = $this->tab_name_row_count_dict[$tab_name] + $row;
        }else{
            $this->tab_name_row_count_dict[$tab_name] = $row;
        }
    }

    private function do_create_and_get_worksheet() {
        $worksheet_name = $this->php_excel_obj->get_tab_name();
        if($this->_check_create_worksheet()) {
            $this->sheet_count++;
            $this->spreadsheet->createSheet();
            $this->_new_sheet = TRUE;
        }
        $this->spreadsheet->setActiveSheetIndex($this->sheet_count - 1);
        $this->spreadsheet->getActiveSheet()->setTitle($worksheet_name);
    }

    private function _check_create_worksheet() {
        if($this->sheet_count <= 0) return TRUE;

        if($this->php_excel_obj->get_begin_row() > 200000){
            $tab_name = $this->php_excel_obj->get_tab_name();
            $this->tab_name_row_count_dict[$tab_name] = 0;

            return TRUE;
        }

        return FALSE;
    }

    private function init_php_excel_object($data) {
        $this->php_excel_obj = NULL;

        if($data instanceof Php_excel_generator) {
            $this->php_excel_obj = new Php_excel_object();
            $this->php_excel_obj->init_by_json($data->getJson());
        }else{
            $this->php_excel_obj = new Php_excel_object();
            $this->php_excel_obj->init_by_json($data);
        }
        $this->php_excel_obj->set_begin_row($this->get_begin_row());
    }

    public function finish($file_path) {
        $this->do_write($file_path, TRUE);
    }

    public function do_gen_excel_with_data($data) {
        $this->init_php_excel_object($data);
        $this->do_create_and_get_worksheet();
        $this->write_basic_data();
        $this->do_coloring_cell();
        $this->do_border_cell();
        $this->do_align_cell();
        $this->do_font_style_cell();
        $this->do_merge_cell();
        $this->do_summary_row();
        $this->do_free_style_key_cell();
        $this->do_formula_row();
        $this->do_next_url();
        $this->spreadsheet->setActiveSheetIndex(0);
    }

    private function do_next_url() {
        if($this->php_excel_obj->get_next_page_url()) {
            $this->current_url = $this->php_excel_obj->get_next_page_url();
            $this->gen_excel_with_json_data($this->CI->curl->simple_get($this->current_url));
        }
    }

    private function do_summary_row() {
        $this->do_avg_row();
        $this->do_sum_row();
    }

    private function do_avg_row() {
        $header_size = $this->php_excel_obj->get_header_data_row_count();
        $data_size = $this->php_excel_obj->get_data_row_count();
        $sum_row = $this->php_excel_obj->get_begin_row() + $header_size + $data_size + 1;

        foreach($this->php_excel_obj->get_keys() as $index => $key) {
            if(in_array($key, $this->php_excel_obj->get_object(Php_excel_object::$KEY_avg))) {
                $col_name = Php_excel_data_helper::convert_program_position_to_excel_positon($index);
                $cell = $col_name.$sum_row;
                $formula = '=AVERAGE('.$col_name.($header_size + 1).':'.$col_name.($sum_row - 1).')';
                $this->spreadsheet->getActiveSheet()->setCellValue($cell,$formula);
            }
        }
    }

    private function do_formula_row() {
        $formula_row = $this->php_excel_obj->get_object(Php_excel_object::$KEY_formula);
        if(empty($formula_row)) return;
        foreach($this->php_excel_obj->get_object(Php_excel_object::$KEY_formula) as $index => $cell_key_val) {
            foreach($cell_key_val as $cell => $formula) {
                $cell = Php_excel_data_helper::get_excel_position($cell, 0);
                $this->spreadsheet->getActiveSheet()->setCellValue($cell,$formula);
            }
        }
    }

    private function do_sum_row() {
        $header_size = $this->php_excel_obj->get_header_data_row_count();
        $data_size = $this->php_excel_obj->get_data_row_count();
        $sum_row = $this->php_excel_obj->get_begin_row() + $header_size + $data_size + 1;

        foreach($this->php_excel_obj->get_keys() as $index => $key) {
            if(in_array($key, $this->php_excel_obj->get_object(Php_excel_object::$KEY_sums))) {
                $col_name = Php_excel_data_helper::convert_program_position_to_excel_positon($index);
                $cell = $col_name.$sum_row;
                $formula = '=SUM('.$col_name.($header_size + 1).':'.$col_name.($sum_row - 1).')';
                $this->spreadsheet->getActiveSheet()->setCellValue($cell,$formula);
            }
        }
    }

    private function do_write($file_name, $extend = FALSE) {
        $writer = new Xlsx($this->spreadsheet);
        $writer->setPreCalculateFormulas(FALSE);
        $writer->save($file_name);
    }

    private function do_coloring_cell() {
        $color_position_range = $this->php_excel_obj->get_object_position_range_dict(Php_excel_object::$KEY_color_cells);
        foreach($color_position_range as $index => $range_obj) {
            $color = $range_obj[Php_excel_object::$_KEY_CELL_COLOR];
            foreach($range_obj[Php_excel_object::$_KEY_position] as $pos_index => $pos) {
                $positon_range = $pos[0].':'.$pos[1];
                $this->get_current_sheet()->getStyle($positon_range)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($color);
            }
        }
    }

    private function do_free_style_key_cell() {
        $free_stlye_key_position_range = $this->php_excel_obj->get_object_position_range_dict(Php_excel_object::$KEY_free_style_merge_key_range);
        foreach($free_stlye_key_position_range as $index => $range_obj) {
            $free_stlye_key = $range_obj[Php_excel_object::$_KEY_FREE_STYLE_MERGE_KEY_RANGE];
            foreach($range_obj[Php_excel_object::$_KEY_position] as $pos_index => $pos) {
                $this->spreadsheet->getActiveSheet()->setCellValue($pos[0],$free_stlye_key);
                $merge_range = $pos[0].':'.$pos[1];
                $this->get_current_sheet()->mergeCells($merge_range);
            }
        }
    }

    private function do_border_cell() {
        $boder_position_range = $this->php_excel_obj->get_object_position_range_dict(Php_excel_object::$KEY_boder_cells);
        foreach($boder_position_range as $index => $range_obj) {
            $border_style = $range_obj[Php_excel_object::$_KEY_BORDER_STYLE];
            $border_weight = $range_obj[Php_excel_object::$_KEY_BORDER_WEIGHT];
            eval('$border_weight = \PhpOffice\PhpSpreadsheet\Style\Border::'.$border_weight.';');
            $border_color = $range_obj[Php_excel_object::$_KEY_BORDER_COLOR];
            foreach($range_obj[Php_excel_object::$_KEY_position] as $pos_index => $pos) {
                $positon_range = $pos[0].':'.$pos[1];
                $styleArray = [
                    'borders' => [
                    $border_style => [
                            'borderStyle' => $border_weight,
                            'color' => ['argb' => $border_color]
                        ],
                    ],
                ];
                $this->get_current_sheet()->getStyle($positon_range)->applyFromArray($styleArray);
            }
        }
    }

    private function do_align_cell() {
        $align_position_range = $this->php_excel_obj->get_object_position_range_dict(Php_excel_object::$KEY_align_cells);
        foreach($align_position_range as $index => $range_obj) {
            $h_align = $range_obj[Php_excel_object::$_KEY_ALIGN_H];
            $v_align = $range_obj[Php_excel_object::$_KEY_ALIGN_V];
            foreach($range_obj[Php_excel_object::$_KEY_position] as $pos_index => $pos) {
                $positon_range = $pos[0].':'.$pos[1];
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getAlignment()->setHorizontal($h_align);
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getAlignment()->setVertical($v_align);
            }
        }
    }

    private function do_font_style_cell() {
        $font_style_position_range = $this->php_excel_obj->get_object_position_range_dict(Php_excel_object::$KEY_font_style);
        foreach($font_style_position_range as $index => $range_obj) {
            $font_weight = $range_obj[Php_excel_object::$_KEY_FONT_STYLE];
            $font_color = $range_obj[Php_excel_object::$_KEY_FONT_COLOR];
            $font_size = $range_obj[Php_excel_object::$_KEY_FONT_SIZE];
            foreach($range_obj[Php_excel_object::$_KEY_position] as $pos_index => $pos) {
                $positon_range = $pos[0].':'.$pos[1];
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getFont()->getColor()->setARGB($font_color);
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getFont()->setSize($font_size);
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getFont()->setBold((strpos($font_weight, 'bold') > -1));
                $this->get_current_sheet()->getStyle($positon_range)
                    ->getFont()->setItalic((strpos($font_weight, 'italic') > -1 ));
            }
        }
    }

    private function do_merge_cell() {
        $cell_range_to_merge_arr = $this->php_excel_obj->get_object_position_range_array(Php_excel_object::$KEY_merge_cell_row_col);
        foreach($cell_range_to_merge_arr as $index => $merge_positions) {
            $merge_range_arr = $merge_positions[Php_excel_object::$_KEY_position];
            $merge_range = $merge_range_arr[0].':'.$merge_range_arr[1];
            $this->get_current_sheet()->mergeCells($merge_range);
        }
    }

    private function write_basic_data() {
        $start_position = $this->php_excel_obj->get_start_position();

        $header = $this->php_excel_obj->get_header_data();
        $data = $this->php_excel_obj->get_data();

        if($this->_new_sheet === TRUE){
            $rows = array_merge($header, $data);
            $this->_new_sheet = FALSE;
        }else{
            $rows = $data;
        }
        $this->get_current_sheet()->fromArray(
            $rows,
            NULL,
            $start_position,
            TRUE
        );

        $total_row = count($rows);

        $this->update_key_tabname_with_row_count_to_dict($total_row);
    }

}
