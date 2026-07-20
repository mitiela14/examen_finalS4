<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixe';
    protected $primaryKey    = 'id_prefixe';
    protected $allowedFields = ['code',
                                'operateur'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function isValid(string $telephone): bool
    {
        if (strlen($telephone) < 3) {
            return false;
        }

        $prefixe = substr($telephone, 0, 3);
        return (bool) $this->where('code', $prefixe)->first();
    }


    public function getOperateurByTelephone(string $telephone): ?string
    {
        $prefixeCode = substr($telephone, 0, 3);
        $prefixe     = $this->where('code', $prefixeCode)->first();

        return $prefixe['operateur'] ?? null;
    }

    public function isOperateurInterne(string $telephone): bool
    {
        return $this->getOperateurByTelephone($telephone) === 'telma';
    }

    public function findByOperateur(string $operateur): array
    {
        return $this->where('operateur', $operateur)->findAll();
    }
}
