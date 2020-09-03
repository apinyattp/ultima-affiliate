<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_report_conversion extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_report_conversion_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('report_conversion');
	}

	private function create_report_conversion_table()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'conversion_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'Conversion ID'
			),
			'uid' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 40,
				'comment' => 'User ID'
			),
			'site_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'Number of reward point site'
			),
			'site_name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Name of reward point site'
			), 
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'Campaign ID'
			),
			'campaign_name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Campaign Name'
			),
			'customerType' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 20,
				'comment' => 'customer Type'
			),
			'creative_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'ID of ACCESSTRADE banner which user clicked'
			),
			'creative_name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Name of ACCESSTRADE banner which user clicked'
			),
			'verification_id' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Merchant transaction ID'
			),
			'click_time' => array(
				'type' => 'DATETIME',
				'null' => FALSE,
				'comment' => 'yyyy-MM-dd HH:mm:ss.0 date when the user clicked link'
			),
			'conversion_time' => array(
				'type' => 'DATETIME',
				'null' => FALSE,
				'comment' => 'yyyy-MM-dd HH:mm:ss.0 date when the conversion occurred'
			),
			'confirmation_time' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
				'comment' => 'Confirmation Time'
			),
			'status' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'constraint' => [
					'PENDING',
					'APPROVED',
					'REJECTED'
				],
				'comment' => ''
			),
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
			'session_id' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => ''
			),
			'parameters' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => ''
			),
			'products' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => 'Merchant product detail'
			),
			'other_parameters' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => 'Other parameters'
			),
			'source' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Source of conversion'
			),
		));
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('conversion_id');
		$this->dbforge->add_key('status');
		$this->dbforge->add_key('reward');
		$this->dbforge->add_key('uid');
		$this->dbforge->add_key('site_id');
		$this->dbforge->add_key('campaign_id');
		$this->dbforge->create_table('report_conversion', TRUE);
	}
}

/* End of file 20200813062937_create_report_conversion.php */
/* Location: ./application/migrations/20200813062937_create_report_conversion.php */
