<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table = 'epargne';
    protected $primaryKey = 'id';
    protected $allowedFields = ['client_id', 'solde_epargne'];
}

public function getEpargneByClientId($clientId)
{
    if (empty($clientId)) {
        return null;
    }
    return $this->where('client_id', $clientId)->first(); 
}   