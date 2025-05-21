<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','phone','email','dep_id','prov_id','dist_id','zip','address','latitude','longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'dep_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'prov_id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'dist_id');
    }

}
