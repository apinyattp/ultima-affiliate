<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_column_status_missing_conversion extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_column_status_missing_conversion_table();
	}

	public function down()
	{
		$this->dbforge->add_column('missing_conversion', 'status');
	}

	private function create_column_status_missing_conversion_table()
	{
		$fields = array(
			'status' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => 'send_to_advertiser',
				'comment' => 'status',
				'after' => 'comefrom'
			),
		);

		$this->dbforge->add_column('missing_conversion', $fields);
	}
}

/* End of file 20210801080156_add_column_status_missing_conversion.php */
/* Location: ./application/migrations/20210801080156_add_column_status_missing_conversion.php */
