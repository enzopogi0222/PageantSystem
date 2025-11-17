<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContestantProfileFields extends Migration
{
    public function up()
    {
        $fields = [
            'age' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'hometown' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'height' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'weight' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'education' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'hobbies' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('contestants', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('contestants', [
            'age', 'hometown', 'height', 'weight', 'education', 'occupation', 'hobbies', 'bio'
        ]);
    }
}
