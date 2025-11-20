<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContestantsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'contestant_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['male', 'female', 'other'],
                'null'       => true,
            ],
            // Profile fields merged from AddContestantProfileFields
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
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'disqualified', 'withdrawn'],
                'default'    => 'active',
            ],
            'photo_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Optional index for filtering by event
        $this->forge->addKey('event_id');
        $this->forge->createTable('contestants');
    }

    public function down()
    {
        $this->forge->dropTable('contestants');
    }
}
