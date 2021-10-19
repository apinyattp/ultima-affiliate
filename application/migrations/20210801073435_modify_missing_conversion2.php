<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_missing_conversion2 extends CI_Migration
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
		#$this->dbforge->drop_column('missing_conversion', 'column_name');
	}

	private function missing_conversion_table()
	{
		$fields = array('order_id' => array(
				'name' => 'order_id',
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
					);

		$this->dbforge->modify_column('missing_conversion', $fields);
	}
}

/* End of file 20210801073435_modify_missing_conversion2.php */
/* Location: ./application/migrations/20210801073435_modify_missing_conversion2.php */
