<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $table = 'distritos';
    protected $fillable = ['nombre', 'prov_id', 'dep_id'];

    /**
     * Relación con Provincia
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Relación con Departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
