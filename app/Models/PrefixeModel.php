<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixe';
    protected $primaryKey    = 'id_prefixe';
    protected $allowedFields = ['code'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function isValid(string $telephone): bool
    {
        $prefixe = substr($telephone, 0, 3);
        return (bool) $this->where('code', $prefixe)->first();
    }
}
