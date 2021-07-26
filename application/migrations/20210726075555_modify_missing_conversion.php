<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_missing_conversion extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->missing_conversion_table();
	}

	public function down()
	{
		/**
		 * Uncomment bellow if you need to destroy the columns you modify.
		*/
		$this->dbforge->drop_column('missing_conversion', 'comefrom');
	}

	private function missing_conversion_table()
	{
		$fields = array(
			'comefrom' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
				'comment' => 'company',
				'after' => 'order_date'
			),
		);

		$this->dbforge->add_column('missing_conversion', $fields);
	}
}

/* End of file 20210726075555_modify_missing_conversion.php */
/* Location: ./application/migrations/20210726075555_modify_missing_conversion.php */
