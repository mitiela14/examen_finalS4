<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'admin';
    protected $primaryKey    = 'id_admin';
    protected $allowedFields = ['nom', 'code_acces'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function findByCode(string $code)
    {
        return $this->where('code_acces', $code)->first();
    }
}
