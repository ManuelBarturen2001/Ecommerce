<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetiroTienda extends Model
{
    use HasFactory;

    protected $table = 'retiro_en_tienda';
    protected $fillable = [
        'nombre_tienda',
        'direccion',
        'ciudad',
        'telefono',
        'horario_apertura',
        'horario_cierre', 
        'dias_disponibles',
        'latitud',
        'longitud',
        'instrucciones_retiro',
        'documentos_requeridos',
        'estado',
        'content'
    ];
}