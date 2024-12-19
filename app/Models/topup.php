<?php

namespace App\Models;

use App\Models\card;
use App\Models\membership;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class topup extends Model
{
    use HasFactory;
    protected $fillable = [
        'topup_datetime', 'membership_id', 'card_id', 'nominal'
        ];
        public function membership(){
            return $this->hasOne(membership::class, 'id', 'membership_id');
            }
            public function card(){
            return $this->hasOne(card::class, 'id', 'card_id');
            }
}
