<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
    public function favor_users(){
        return $this->belongsToMany(User::class,"favouritas","item_id","user_id")->withTimestamps();
    }
    public function seller(){
        return $this->belongsTo(Seller::class,"seller_id");
    }
    public function images(){
        return $this->hasMany(Upload::class,'product_id');
    }

}
