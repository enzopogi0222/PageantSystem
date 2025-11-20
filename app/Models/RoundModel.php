<?php

namespace App\Models;

use CodeIgniter\Model;

class RoundModel extends Model
{
    protected $table            = 'rounds';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'event_id',
        'name',
        'description',
        'round_order',
        'status',
        'max_score',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
