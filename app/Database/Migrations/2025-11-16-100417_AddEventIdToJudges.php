<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEventIdToJudges extends Migration
{
    public function up()
    {
        $fields = [
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'after'      => 'user_id',
            ],
        ];

        $this->forge->addColumn('judges', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('judges', 'event_id');
    }
}