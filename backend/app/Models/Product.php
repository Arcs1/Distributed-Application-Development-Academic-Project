<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'id',
        'name',
        'type',
        'description',
        'photo_url',
        'price'
    ];

    public function orderItem(){
        return $this->belongsTo('App\Models\OrderItem','id','product_id');
    }
}
