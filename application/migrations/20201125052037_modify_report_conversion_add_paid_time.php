<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_report_conversion_add_paid_time extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->report_conversion_add_paid_time_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('report_conversion', 'paid_time');
		$fields = array(
			'reward' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Publish reward calculated according to sales amount of user's order"
			),
			'transaction_amount' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Sales amount of user's order"
			),
		);
		$this->dbforge->modify_column('report_conversion', $fields);
	}

	private function report_conversion_add_paid_time_table()
	{
		$fields = array(
			'reward' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Publish reward calculated according to sales amount of user's order"
			),
			'transaction_amount' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Sales amount of user's order"
			),
		);

		$this->dbforge->modify_column('report_conversion', $fields);

		$fields = array(
			'paid_time' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
				'comment' => 'yyyy-MM-dd HH:mm:ss.0 datetime when paid',
				'after' => 'confirmation_time'
			),
		);

		$this->dbforge->add_column('report_conversion', $fields);
	}
}

/* End of file 20201125052037_modify_report_conversion_add_paid_time.php */
/* Location: ./application/migrations/20201125052037_modify_report_conversion_add_paid_time.php */
