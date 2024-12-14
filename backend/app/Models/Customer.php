<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model

{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'user_id',
        'phone',
        'points',
        'nif',
        'default_payment_type',
        'default_payment_reference'

    ];

    public function user(){
        return $this->belongsTo('App\Models\User','id','user_id');
    }
    public function order(){
        return $this->hasMany('App\Models\Order');
    }
}
