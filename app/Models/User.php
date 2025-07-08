<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Definición de roles para evitar errores de tipeo.
     */
    public const ROLE_LIDER_PROYECTO_ANALISTA = 'Analista';
    public const ROLE_SUPERVISOR = 'Supervisor';
    public const ROLE_OPERADOR_LOGISTICO = 'Operador_Logístico';
    public const ROLE_COORDINADOR_ADMINISTRATIVO = 'Coordinador_Administrativo';

    public static array $availableRoles = [
        self::ROLE_LIDER_PROYECTO_ANALISTA,
        self::ROLE_SUPERVISOR,
        self::ROLE_OPERADOR_LOGISTICO,
        self::ROLE_COORDINADOR_ADMINISTRATIVO,
    ];

    /**
     * Atributos que se pueden asignar masivamente.
     * supervisor_id no está aquí porque la relación es indirecta.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'codigo_supervisor',
        'unidad_trabajo_id',
    ];

    /**
     * Atributos ocultos para serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversiones de tipos de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ================================================================
    //                     RELACIONES ELOQUENT
    // ================================================================

    /**
     * RELACIÓN: Un Operador pertenece a UNA Unidad de Trabajo.
     * Esto nos permite hacer $operador->unidadTrabajo
     */
    public function unidadTrabajo()
    {
        return $this->belongsTo(UnidadTrabajo::class, 'unidad_trabajo_id');
    }

    /**
     * RELACIÓN CLAVE: Obtener el supervisor del operador A TRAVÉS de la unidad de trabajo.
     * Esta es la única fuente de verdad para el supervisor.
     * Nos permite hacer $operador->supervisor
     */
    public function supervisor()
    {
        return $this->hasOneThrough(
            User::class,            // Modelo final: Supervisor (User)
            UnidadTrabajo::class,   // Modelo intermedio: Unidad de Trabajo
            'id',                   // Llave en UnidadTrabajo que se relaciona con User (supervisor)
            'id',                   // Llave en User (supervisor) que se relaciona con UnidadTrabajo
            'unidad_trabajo_id',    // Llave local en este modelo (User/Operador)
            'supervisor_id'         // Llave foránea en el modelo intermedio (UnidadTrabajo)
        )->where('users.rol', self::ROLE_SUPERVISOR); // Filtro extra de seguridad
    }
}