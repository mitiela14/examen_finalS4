<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueModel extends Model
{
    protected $table         = 'historique_client';
    protected $primaryKey    = 'id_historique';
    protected $allowedFields = [
        'id_utilisateur', 'id_type_operation', 'montant', 'frais',
        'telephone_destinataire', 'solde_apres',
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    public function historiqueClient(int $idUtilisateur)
    {
        return $this->select('historique_client.*, type_operation.libelle as type_libelle')
                    ->join('type_operation', 'type_operation.id_type_operation = historique_client.id_type_operation')
                    ->where('id_utilisateur', $idUtilisateur)
                    ->orderBy('date_operation', 'DESC')
                    ->findAll();
    }

    public function totalGainsParType()
    {
        return $this->select('type_operation.libelle as type_operation, SUM(historique_client.frais) as total_frais, COUNT(*) as nombre')
                    ->join('type_operation', 'type_operation.id_type_operation = historique_client.id_type_operation')
                    ->groupBy('type_operation.libelle')
                    ->findAll();
    }
}
