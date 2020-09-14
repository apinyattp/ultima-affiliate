<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_callback_data_add_source extends CI_Migration
{
	public function __construct() {
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		$this->callback_data_add_source_table();
	}

	public function down() {
		/**
		 * Uncomment bellow if you need to destroy the columns you modify.
		 */
		$this->dbforge->drop_column('callback_data', 'source');
	}

	private function callback_data_add_source_table() {
		$fields = array(
			'source' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 255,
				'comment' => 'Source of callback data',
				'after' => 'id'
			),
		);

		$this->dbforge->add_column('callback_data', $fields);
	}
}

/* End of file 20200913055943_modify_callback_data_add_source.php */
/* Location: ./application/migrations/20200913055943_modify_callback_data_add_source.php */
