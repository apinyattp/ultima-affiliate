<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once VENDORPATH.'autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Reader\ReaderFactory;
class Spout_excel
{
    private $_ci;
    private $_writer;
    private $_reader;

    public function __construct() {
        // Get CI object.
        $this->_ci =& get_instance();
    }

    public function new_file($file_path, $type='xlsx') {
        $this->_init_writer($type);
        $this->_writer->openToFile($file_path);
    }

    public function new_stream($file_name, $type='xlsx') {
        $this->_init_writer($type);
        $this->_writer->openToBrowser($file_name);
    }

    private function _init_writer($type) {
        $this->_writer = WriterFactory::create($type);
    }

    public function add_row($a_row, $type="normal") {

        switch($type) {
            case 'header':
                $style = (new StyleBuilder())
                           ->setFontColor(Color::BLUE)
                        ->build();
                $this->_writer->addRowWithStyle($a_row, $style);
                break;

            default:
                $this->_writer->addRow($a_row);
                break;
        }
    }

    public function add_header($a_row) {
        $this->add_row($a_row, "header");
    }

    public function add_sheet() {
        $this->_writer->addNewSheetAndMakeItCurrent();
    }

    public function sheet_name($name) {
        $this->_writer->getCurrentSheet()->setName($name);
    }

    public function goto_sheet($sheet_index) {
        $a_sheet = $this->_writer->getSheets();
        if(!isset($a_sheet[$sheet_index])) return FALSE;
        $this->_writer->setCurrentSheet($a_sheet[$sheet_index]);
        return TRUE;
    }

    public function close() {
        if($this->_writer) $this->_writer->close();
        if($this->_reader) $this->_reader->closeReader();
    }

    public function reader($file_path, $delimiter=NULL, $enclosure=NULL, $eol=NULL) {
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        switch($ext) {
            case Type::XLSX:
                $reader = ReaderFactory::create(Type::XLSX);
                break;
            case Type::CSV:
                $reader = ReaderFactory::create(Type::CSV);
                if($delimiter) $reader->setFieldDelimiter($delimiter);
                if($enclosure) $reader->setFieldEnclosure($enclosure);
                if($eol) $reader->setEndOfLineCharacter($eol);
                break;
            default:
                return FALSE;
        }

        $reader->open($file_path);
        return $reader;
    }

}
