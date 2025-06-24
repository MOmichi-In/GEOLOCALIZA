<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadTrabajo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación inversa (opcional, pero útil)
    public function users()
    {
        return $this->hasMany(User::class, 'unidad_trabajo_id');
    }
}