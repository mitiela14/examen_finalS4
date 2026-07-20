<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeOperateurModel extends Model
{
    protected $table         = 'solde_operateur';
    protected $primaryKey    = 'id_solde';
    protected $allowedFields = ['operateur', 'montant_a_envoyer'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function ajouterMontant(string $operateur, float $montant): void
    {
        $row = $this->where('operateur', $operateur)->first();
        if (is_array($row) && isset($row['id_solde'])) {
            $this->update($row['id_solde'], [
                'montant_a_envoyer' => (float) $row['montant_a_envoyer'] + $montant,
            ]);
        }
    }

    public function getMontant(string $operateur): float
    {
        $row = $this->where('operateur', $operateur)->first();
        return (is_array($row) && isset($row['montant_a_envoyer'])) ? (float) $row['montant_a_envoyer'] : 0;
    }
}
