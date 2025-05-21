<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;
    protected $fillable = ['end_date', 'id'];
    public function items()
    {
        return $this->hasMany(FlashSaleItem::class);
    }
}
