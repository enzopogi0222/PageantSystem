<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToEvents extends Migration
{
    public function up()
    {
        $fields = [
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'after' => 'location',
            ],
        ];
        $this->forge->addColumn('events', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('events', 'is_active');
    }
}
