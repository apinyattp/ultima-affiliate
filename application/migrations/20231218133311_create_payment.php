<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_payment extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->create_payment_table();
    }

    public function down()
    {
        $this->dbforge->drop_table('payment');
    }

    private function create_payment_table()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'null' => FALSE,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ),
            'source' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 50,
            ),
            'invoice_no' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 50,
            ),
            'amount_total' => array(
                'type' => 'DOUBLE',
                'null' => FALSE,
                'unsigned' => TRUE,
                'constraint' => [18, 2],
            ),
            'amount_paid' => array(
                'type' => 'DOUBLE',
                'null' => FALSE,
                'unsigned' => TRUE,
                'constraint' => [18, 2],
            ),
            'amount_vat' => array(
                'type' => 'DOUBLE',
                'null' => FALSE,
                'constraint' => [18, 2],
            ),
            'amount_wht' => array(
                'type' => 'DOUBLE',
                'null' => FALSE,
                'constraint' => [18, 2],
            ),
            'amount_member' => array(
                'type' => 'DOUBLE',
                'null' => FALSE,
                'constraint' => [18, 2],
            ),
            'datetime_paid' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['source', 'invoice_no']);
        $this->dbforge->create_table('payment', TRUE);
    }
}

/* End of file 20231218133311_create_payment.php */
/* Location: ./application/migrations/20231218133311_create_payment.php */
