<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RetiroTienda;
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['nombris_store_pickup', 'store_pickup_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function storePickup()
    {
        return $this->belongsTo(RetiroTienda::class, 'store_pickup_id');
    }
}
