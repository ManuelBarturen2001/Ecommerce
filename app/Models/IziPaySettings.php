<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IziPaySettings extends Model
{
    use HasFactory;
    protected $table = 'izipay_settings';
    protected $fillable = [
        'status',
        'mode',
        'country_name',
        'currency_name',
        'currency_rate',
        'shop_id',
        'public_key',
        'private_key',
        'hmac_sha256_key',
        'javascript_client_key'
    ];

    protected $casts = [
        'status' => 'boolean',
        'currency_rate' => 'decimal:2'
    ];
}
