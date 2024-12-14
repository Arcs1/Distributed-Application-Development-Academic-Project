<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable=[
        'order_id',
        'order_local_number',
        'product_id',
        'status',
        'price',
        'preparation_by',
        'notes'
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order','order_id','id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }
}
