<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Descomenta si usas verificación de email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Asegúrate de tener Hash si no usas el cast 'hashed' y tienes un mutador setPasswordAttribute
// use Illuminate\Support\Facades\Hash;

class User extends Authenticatable // implements MustVerifyEmail (si lo usas)
{
    use HasFactory, Notifiable;

    /**
     * Los roles definidos para el sistema.
     * Estos strings deben coincidir con los valores que guardarás/usas en la columna 'rol' de tu tabla 'users'.
     */
    public const ROLE_LIDER_PROYECTO_ANALISTA = 'Analista';
    public const ROLE_SUPERVISOR = 'Supervisor';
    public const ROLE_OPERADOR_LOGISTICO = 'Operador_Logístico';
    public const ROLE_COORDINADOR_ADMINISTRATIVO = 'Coordinador_Administrativo';

    /**
     * Un array con todos los roles disponibles.
     * Útil para generar selectores en formularios de creación/edición de usuarios.
     */
    public static array $availableRoles = [
        self::ROLE_LIDER_PROYECTO_ANALISTA,
        self::ROLE_SUPERVISOR,
        self::ROLE_OPERADOR_LOGISTICO,
        self::ROLE_COORDINADOR_ADMINISTRATIVO,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol', // Esta columna almacenará uno de los strings de las constantes de rol
        'unidad_trabajo_id',
        'supervisor_id',
    ];

    // Añade la relación
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // También es útil tener la relación inversa
    public function operadoresASuCargo()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel se encarga de hashear automáticamente
        ];
    }

    /**
     * Relación con Unidad de Trabajo.
     */
    public function unidadTrabajo()
    {
        return $this->belongsTo(UnidadTrabajo::class, 'unidad_trabajo_id');
    }

    // Puedes añadir aquí otras relaciones si las necesitas con frecuencia,
    // por ejemplo, si un supervisor tiene muchas asistencias registradas por él:
    // public function asistenciasRegistradas()
    // {
    //     return $this->hasMany(RegistroAsistencia::class, 'registrado_por');
    // }

    // Si un operador tiene muchas tareas asignadas:
    // public function tareasAsignadas()
    // {
    //     return $this->hasMany(TareaAsignada::class, 'operador_id');
    // }
}