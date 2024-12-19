<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class membership extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','address','total_belanja','total_topup'
    ];
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
        }
}
