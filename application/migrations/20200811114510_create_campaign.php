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
				'display_name' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Campaign display name on application'
				),
				'image_file_id' => array(
					'type' => 'INT',
					'null' => TRUE,
					'unsigned' => TRUE,
				),
				'cashback' => array(
                    'type' => 'VARCHAR',
                    'null' => FALSE,
					'constraint' => 10,
					'comment' => 'Redeemable in (Day)'
				),
				'description' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Description'
				),
				'condition_do' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Do'
				),
				'condition_dont' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => "Don't"
				),
				'note' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Note'
				),
				'sort' => array(
                    'type' => 'TINYINT',
                    'null' => FALSE,
                    'default' => 0,
					'comment' => 'Campaign Sort'
				),
				'status' => array(
                    'type' => 'ENUM',
                    'null' => FALSE,
                    'constraint' => [
						'inactive',
                        'active'
					],
					'comment' => 'Status'
				),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'null' => FALSE,
                    'default' => 0
				)
			)
		);
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('status');
		$this->dbforge->add_key('sort');
		$this->dbforge->add_key('deleted');
		$this->dbforge->create_table('campaign', TRUE);
	}

}

/* End of file 20200811114510_create_campaign.php */
/* Location: ./application/migrations/20200811114510_create_campaign.php */
