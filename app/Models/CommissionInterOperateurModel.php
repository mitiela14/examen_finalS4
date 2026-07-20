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
}
