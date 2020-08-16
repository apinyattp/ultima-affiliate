<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign extends CI_Migration {

	public function __construct() {
        $this->load->dbforge();
        $this->load->database();
    }

	public function up() {
		$this->create_campaign_table();
	}

	public function down() {
		$this->dbforge->drop_table('campaign');
	}

	private function create_campaign_table() {
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'auto_increment' => TRUE,
					'unsigned' => TRUE,
					'constraint' => 5
				),
				'name' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Campaign name'
				),
				'source' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Source of affiliate campaign'
				),
				'url' => array(
					'type' => 'TEXT',
					'null' => FALSE,
					'comment' => 'Campaign URL'
				),
				'quicklink' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Campaign Qicklink'
				),
				'image_url' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255
				),
				'logo_image_url' => array(
					'type' => 'VARCHAR',
					'null' => TRUE,
					'constraint' => 255
				),
				'default_reward' => array(
					'type' => 'TEXT',
					'null' => FALSE,
					'comment' => 'Default reward description from source'
				),
				'reward' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Reward description'
				),
				'status' => array(
                    'type' => 'ENUM',
                    'null' => FALSE,
                    'constraint' => [
                        'active',
                        'inactive',
                    ]
				),
				'affiliated_date' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'null' => FALSE,
                    'default' => 0
				)
			)
		);
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('status');
		$this->dbforge->add_key('deleted');
		$this->dbforge->create_table('campaign', TRUE);
	}

}

/* End of file 20200811114510_create_campaign.php */
/* Location: ./application/migrations/20200811114510_create_campaign.php */
