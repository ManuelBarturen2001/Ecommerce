<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingRateDistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_rule_id',
        'min_km',
        'max_km',
        'price',
    ];

    /**
     * Relación con la regla de envío (shipping_rules).
     */
    public function shippingRule()
    {
        return $this->belongsTo(ShippingRule::class);
    }
}
