<?php

namespace App\Models;

use CodeIgniter\Model;

class ContestantModel extends Model
{
    protected $table            = 'contestants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contestant_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'status',
        'photo_path'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
