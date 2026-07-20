<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table         = 'type_operation';
    protected $primaryKey    = 'id_type_operation';
    protected $allowedFields = ['libelle'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function findByLibelle(string $libelle)
    {
        return $this->where('libelle', $libelle)->first();
    }
}
