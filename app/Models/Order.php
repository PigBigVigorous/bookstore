<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 'receiver_name', 'receiver_phone', 
        'receiver_address', 'note', 'total_price', 'status'
    ];

    public function details() {
        return $this->hasMany(OrderDetail::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
