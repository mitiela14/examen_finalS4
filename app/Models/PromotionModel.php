<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table         = 'promotion';
    protected $primaryKey    = 'id_promotion';
    protected $allowedFields = ['pourcentage', 'date_expiration'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

 public function getPromotion(): ?array
    {
        return $this->orderBy('date_expiration', 'DESC')->first();
    }
}
