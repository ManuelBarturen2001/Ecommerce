<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;
    
    protected $table = 'provincias';
    protected $fillable = ['nombre', 'dep_id'];

    /**
     * Obtener el departamento al que pertenece la ciudad
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function distrito(){
        return $this->hasMany(Distrito::class);
    }

}