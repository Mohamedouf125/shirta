<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
    public function user(){
        return $this->belongsTo(user::class,'user_id');
    }
    public function items(){
        return $this->hasMany(user::class, 'seller_id');
    }
}
