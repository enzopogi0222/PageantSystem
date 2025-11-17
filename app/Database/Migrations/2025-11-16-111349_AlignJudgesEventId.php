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

        // Only add the foreign key if the events table already exists to avoid
        // migration ordering issues that cause errno 150.
        $row = $this->db->query(
            "SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events'"
        )->getRowArray();
        $eventsExists = isset($row['cnt']) && (int) $row['cnt'] > 0;

        if ($eventsExists) {
            try {
                $this->db->query('ALTER TABLE judges ADD CONSTRAINT judges_event_id_foreign FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE ON UPDATE CASCADE');
            } catch (\Throwable $e) {
                // If FK already exists or cannot be created now, skip silently.
            }
        }
    }
    public function down()
    {
        // Drop FK if present
        try { $this->db->query('ALTER TABLE judges DROP FOREIGN KEY judges_event_id_foreign'); } catch (\Throwable $e) {}

        
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