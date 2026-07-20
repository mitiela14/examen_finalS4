<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table            = 'utilisateur';
    protected $primaryKey       = 'id_utilisateur';
    protected $allowedFields    = ['telephone', 'solde'];
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    public function findByTelephone(string $telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }
}
