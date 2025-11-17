<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAgeHometownToContestants extends Migration
{
    public function up()
    {
        $fields = [
            'age' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'phone', // adjust if you want a different position
            ],
            'hometown' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'age',
            ],
        ];

        $this->forge->addColumn('contestants', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('contestants', ['age', 'hometown']);
    }
}