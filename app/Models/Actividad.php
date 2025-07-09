<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    
    /**
     * Le decimos a Laravel a qué tabla está conectado este modelo.
     */
    protected $table = 'actividades';

    /**
     * Le decimos a Laravel qué campos de esta tabla se pueden rellenar de forma masiva.
     * Esto es una medida de seguridad.
     */
    protected $fillable = ['nombre'];

    /**
     * Este modelo no necesita definir relaciones por ahora,
     * ya que las "Actividades" no dependen de ninguna otra tabla.
     */
}