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

    public function getCommission(string $operateur): float
    {
        $row = $this->where('operateur', $operateur)->first();
        return (is_array($row) && isset($row['pourcentage_autres'])) ? (float) $row['pourcentage_autres'] : 0;
    }

    public function updatePourcentage(string $operateur, float $pourcentage): void
    {
        $existing = $this->where('operateur', $operateur)->first();

        if (is_array($existing)) {
            $this->update($existing['id_commission'], ['pourcentage_autres' => $pourcentage]);
        } else {
            $this->insert(['operateur' => $operateur, 'pourcentage_autres' => $pourcentage]);
        }
    }
}
