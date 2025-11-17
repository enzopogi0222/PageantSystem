<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEventIdToContestants extends Migration
{
    public function up()
    {
        $fields = [
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ];

        $this->forge->addColumn('contestants', $fields);

        // Add index for faster filtering
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_contestants_event_id ON contestants(event_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('contestants', ['event_id']);
    }
}
