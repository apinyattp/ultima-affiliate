<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_conversion_column_status extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->conversion_column_status_table();
	}

	public function down()
	{
		$fields = array(
			'status' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'constraint' => [
					'PENDING',
					'APPROVED',
					'REJECTED',
					'NEW',
				],
				'comment' => 'Conversion Status'
			),
		);
		$this->dbforge->modify_column('report_conversion', $fields);
	}

	private function conversion_column_status_table()
	{
		$fields = array(
			'status' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'constraint' => [
					'PENDING',
					'APPROVED',
					'REJECTED',
					'NEW',
					'PAID'
				],
				'comment' => 'Conversion Status'
			),
		);
		$this->dbforge->modify_column('report_conversion', $fields);
	}
}

/* End of file 20201123040355_modify_conversion_column_status.php */
/* Location: ./application/migrations/20201123040355_modify_conversion_column_status.php */
