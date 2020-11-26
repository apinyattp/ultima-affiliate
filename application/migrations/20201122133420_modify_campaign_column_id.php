<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_column_id extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->campaign_column_id_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('campaign', 'code');
	}

	private function campaign_column_id_table()
	{
		$fields = array(
			'code' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 10,
				'comment' => 'Campaign Code',
				'after' => 'id'
			),
		);

		$this->dbforge->add_column('campaign', $fields);
	}
}

/* End of file 20201122133420_modify_campaign_column_id.php */
/* Location: ./application/migrations/20201122133420_modify_campaign_column_id.php */
