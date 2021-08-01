<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_is_missing extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->is_missing_table();
	}

	public function down()
	{
		/**
		 * Uncomment bellow if you need to destroy the columns you modify.
		 */
		$this->dbforge->drop_column('report_conversion', 'missing_id');
	}

	private function is_missing_table()
	{
		$fields = array(
			'missing_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
				'comment' => 'missing_id',
				'after' => 'source'
			),
		);

		$this->dbforge->add_column('report_conversion', $fields);
	}
}

/* End of file 20210801090138_modify_is_missing.php */
/* Location: ./application/migrations/20210801090138_modify_is_missing.php */
