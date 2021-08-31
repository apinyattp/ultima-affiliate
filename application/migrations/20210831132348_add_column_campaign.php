<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_column_campaign extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_column_campaign_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('campaign', 'maximum_commission');
	}

	private function create_column_campaign_table()
	{
		$fields = array(
			'maximum_commission' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'default' => 0,
				'comment' => 'maximum commission (fix rate)',
				'after' => 'note'
			),
		);

		$this->dbforge->add_column('campaign', $fields);
	}
}

/* End of file 20210831132348_add_column_campaign.php */
/* Location: ./application/migrations/20210831132348_add_column_campaign.php */
