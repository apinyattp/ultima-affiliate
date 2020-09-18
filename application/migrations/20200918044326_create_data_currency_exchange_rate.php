<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_data_currency_exchange_rate extends CI_Migration
{
	public function __construct() {
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		$this->create_data_currency_exchange_rate_table();
	}

	public function down() {
		$this->dbforge->drop_table('data_currency_exchange_rate');
	}

	private function create_data_currency_exchange_rate_table() {
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'date' => array(
				'type' => 'DATE',
				'null' => TRUE,
				'comment' => 'Date of the exchange rate'
			),
			'base' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 3,
				'comment' => 'Source currency code'
			),
			'target' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 3,
				'comment' => 'Destination currency code'
			),
			'rate' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => [18, 10],
				'comment' => 'Exchange rate (base * rate = target)'
			),
			'source' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Source of Data'
			),
		));
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('data_currency_exchange_rate', TRUE);
	}
}

/* End of file 20200918044326_create_data_currency_exchange_rate.php */
/* Location: ./application/migrations/20200918044326_create_data_currency_exchange_rate.php */
