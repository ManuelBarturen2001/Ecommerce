<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulqiSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'mode',
        'country_name',
        'currency_name',
        'currency_rate',
        'public_key',
        'secret_key'
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
}
