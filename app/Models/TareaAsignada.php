<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaAsignada extends Model
{
    //
    protected $fillable = [
        'fecha_trabajo',
        'ciclo',
        'correria', 
        'operador_id'
    ];

    public function operador() {
        return $this->belongsTo(User::class, 'operador_id');
    }

}
