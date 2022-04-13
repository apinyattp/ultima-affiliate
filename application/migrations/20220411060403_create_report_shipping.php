<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_report_shipping extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->create_report_shipping_table();
    }

    public function down()
    {
        $this->dbforge->drop_table('report_shipping');
    }

    private function create_report_shipping_table()
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
                'constraint' => 255,
            ),
            'courier' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 64,
            ),
            'tracking' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 64,
            )
        ));
        $this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['courier', 'tracking']);
        $this->dbforge->create_table('report_shipping', TRUE);
    }
}

/* End of file 20220411060403_create_report_shipping.php */
/* Location: ./application/migrations/20220411060403_create_report_shipping.php */
