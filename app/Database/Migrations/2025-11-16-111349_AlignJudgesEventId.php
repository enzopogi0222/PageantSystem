<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignJudgesEventId extends Migration
{
    public function up()
    {
        
        $this->forge->modifyColumn('judges', [
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

       
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('judges'); 

    }
    public function down()
    {
       
        $this->forge->dropForeignKey('judges', 'judges_event_id_foreign');

       
        $this->forge->modifyColumn('judges', [
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
                'null'       => false,
            ],
        ]);
    }
}