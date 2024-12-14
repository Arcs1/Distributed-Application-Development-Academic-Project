<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'ticket_number',
        'status',
        'total_price',
        'total_paid',
        'total_paid_with_points',
        'points_gained',
        'points_used_to_pay',
        'payment_type',
        'payment_reference',
        'date',
        'delivered_by'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer','id','customer_id');
    }

    public function orderItem(){
        return $this->hasMany('App\Models\OrderItem');
    }
}
