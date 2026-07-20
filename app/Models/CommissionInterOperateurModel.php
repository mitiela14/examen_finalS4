<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionInterOperateurModel extends Model
{
    protected $table         = 'commission_inter_operateur';
    protected $primaryKey    = 'id_commission';
    protected $allowedFields = ['operateur', 'pourcentage_autres'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getByOperateur(string $operateur)
    {
        return $this->where('operateur', $operateur)->first();
    }

    public function updatePourcentage(string $operateur, float $pourcentage): void
    {
        $existing = $this->getByOperateur($operateur);

        if ($existing) {
            $this->update($existing['id_commission'], ['pourcentage_autres' => $pourcentage]);
        } else {
            $this->insert(['operateur' => $operateur, 'pourcentage_autres' => $pourcentage]);
        }
    }
}