<?php

use Illuminate\Support\Facades\Route;

// Importa todos tus componentes y controladores aquí arriba
use App\Http\Controllers\ProfileController;
use App\Livewire\Usuarios;
use App\Livewire\TaskAssignment;
use App\Livewire\Supervisor\PanelTareas;
use App\Livewire\Supervisor\GestionarTarea;
use App\Livewire\GestionUnidadesTrabajo;
use App\Livewire\GestionActividades;
use App\Livewire\GestionCiclos;
use App\Livewire\GestionCorrerias;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS (ACCESIBLES SIN INICIAR SESIÓN) ---
Route::get('/', function () {
    return view('auth.login');
})->name('login.index');

// Archivo de rutas de autenticación de Breeze/Jetstream
require __DIR__ . '/auth.php';

// --- RUTAS PROTEGIDAS (REQUIEREN INICIAR SESIÓN) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Ruta principal después de iniciar sesión
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->rol === 'Supervisor' || $user->rol === 'Coordinador_Administrativo') {
            return redirect()->route('panel.tareas');
        }
        // Puedes añadir más redirecciones para otros roles aquí
        return view('dashboard');
    })->name('dashboard');

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestión de Usuarios
    Route::get('/usuarios', Usuarios::class)
        ->name('users.index')
        ->middleware('role:Coordinador_Administrativo');

    // Gestión de Datos Maestros
    Route::get('/actividades', GestionActividades::class)
        ->name('actividades.index')
        ->middleware('role:Supervisor,Coordinador_Administrativo');
        
    Route::get('/ciclos', GestionCiclos::class)
        ->name('ciclos.index')
        ->middleware('role:Supervisor,Coordinador_Administrativo');

    Route::get('/correrias', GestionCorrerias::class)
        ->name('correrias.index')
        ->middleware('role:Supervisor,Coordinador_Administrativo');
        
    Route::get('/gestion-unidades', GestionUnidadesTrabajo::class)
        ->name('unidades.index')
        ->middleware('role:Coordinador_Administrativo');

    // Gestión Operativa de Tareas
    Route::get('/tareas/asignar', TaskAssignment::class)
        ->name('tasks.assign')
        ->middleware('role:Coordinador_Administrativo,Supervisor');
        
    Route::get('/panel-tareas', PanelTareas::class)
        ->name('panel.tareas')
        ->middleware('role:Supervisor,Coordinador_Administrativo');

    Route::get('/supervisor/tarea/{tarea}', GestionarTarea::class)
        ->name('supervisor.tarea.gestionar')
        ->middleware('role:Supervisor,Coordinador_Administrativo');
});