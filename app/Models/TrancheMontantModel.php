<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheMontantModel extends Model
{
    protected $table         = 'tranche_montant';
    protected $primaryKey    = 'id_tranche';
    protected $allowedFields = ['id_type_operation', 'montant_min', 'montant_max', 'frais'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    /**
     * Retourne le montant des frais applicable pour un type d'operation et un montant donne.
     */
    public function getFrais(int $idTypeOperation, float $montant): float
    {
        $tranche = $this->where('id_type_operation', $idTypeOperation)
                         ->where('montant_min <=', $montant)
                         ->where('montant_max >=', $montant)
                         ->first();

        return $tranche ? (float) $tranche['frais'] : 0;
    }

    public function listByType(int $idTypeOperation)
    {
        return $this->where('id_type_operation', $idTypeOperation)
                     ->orderBy('montant_min', 'ASC')
                     ->findAll();
    }
}
