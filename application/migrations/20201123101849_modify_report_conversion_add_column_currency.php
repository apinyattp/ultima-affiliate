<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_report_conversion_add_column_currency extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->report_conversion_add_column_currency_table();
	}

	public function down() {
		$this->dbforge->drop_column('report_conversion', 'original_reward');
		$this->dbforge->drop_column('report_conversion', 'original_transaction_amount');
		$this->dbforge->drop_column('report_conversion', 'currency');
	}

	private function report_conversion_add_column_currency_table()
	{
		$fields = array(
			'original_reward' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Original reward payout",
				'after' => 'reward'
			),
			'original_transaction_amount' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => "Original Transaction Amount",
				'after' => 'transaction_amount'
			),
			'currency' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 3,
				'comment' => 'Currency for orginal reward',
				'after' => 'original_transaction_amount'
			),
		);

		$this->dbforge->add_column('report_conversion', $fields);
	}
}

/* End of file 20201123101849_modify_report_conversion_add_column_currency.php */
/* Location: ./application/migrations/20201123101849_modify_report_conversion_add_column_currency.php */
