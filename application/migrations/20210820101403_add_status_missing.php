<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_status_missing extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_status_missing_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('missing_conversion', 'status_reject');
	}

	private function create_status_missing_table()
	{
		$fields = array('status' => array(
				'name' => 'status_reject',
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => 'send_to_advertiser',
			)
		);

		$this->dbforge->modify_column('missing_conversion', $fields);

		$fields = array(
			'status' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => 'new',
				'comment' => 'status',
				'after' => 'status_reject'
			),
		);

		$this->dbforge->add_column('missing_conversion', $fields);
	}
}

/* End of file 20210820101403_add_status_missing.php */
/* Location: ./application/migrations/20210820101403_add_status_missing.php */
