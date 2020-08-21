<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_category extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_category_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_category');
	}

	private function create_campaign_category_table()
	{
		$this->dbforge->add_field(array(
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => ''
			),
			'value' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'comment' => ''
			),
			'item' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => ''
			)
		));
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('campaign_id');
		$this->dbforge->create_table('campaign_category', TRUE);
	}
}

/* End of file 20200817091009_create_campaign_category.php */
/* Location: ./application/migrations/20200817091009_create_campaign_category.php */
