<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixe';
    protected $primaryKey    = 'id_prefixe';
    protected $allowedFields = ['code', 'operateur'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function isValid(string $telephone): bool
    {
        $prefixe = substr($telephone, 0, 3);
        return (bool) $this->where('code', $prefixe)->first();
    }

    public function getOperateurByTelephone(string $telephone): ?string
    {
        $prefixe = substr($telephone, 0, 3);
        $row = $this->where('code', $prefixe)->first();
        return (is_array($row) && isset($row['operateur'])) ? $row['operateur'] : null;
    }

    public function isOwnOperator(string $telephone, string $monOperateur = 'airtel'): bool
    {
        $operateur = $this->getOperateurByTelephone($telephone);
        return $operateur === $monOperateur;
    }
}
