<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discount';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'nominal'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts        = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'tanggal' => 'required|is_unique[discount.tanggal,id,{id}]',
        'nominal' => 'required|numeric',
    ];
    protected $validationMessages = [
        'tanggal' => [
            'is_unique' => 'The tanggal field must contain a unique value.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Cari diskon berdasarkan tanggal hari ini
     */
    public function getTodayDiscount()
    {
        $today = date('Y-m-d');
        return $this->where('tanggal', $today)->first();
    }
}
