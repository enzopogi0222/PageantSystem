<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEventIdToContestants extends Migration
{
    public function up()
    {
       
        $this->forge->addColumn('contestants', [
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'after'      => 'id',
            ],
        ]);

      
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('contestants');
    }

    public function down()
    {
       
        $this->forge->dropForeignKey('contestants', 'contestants_event_id_foreign');
        $this->forge->dropColumn('contestants', 'event_id');
    }
}
