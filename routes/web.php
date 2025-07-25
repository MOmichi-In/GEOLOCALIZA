<?php

use App\Livewire\Importacion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Nuevos namespaces con base en tu reestructuración
use App\Livewire\Coordinador\usuarios\Usuarios;
use App\Livewire\Coordinador\asignaciones\AsignacionTareas;
use App\Livewire\supervisor\PanelTareas;
use App\Livewire\supervisor\GestionarTarea;
use App\Livewire\coordinador\gestion\GestionUnidadesTrabajo;
use App\Livewire\coordinador\gestion\GestionActividades;
use App\Livewire\coordinador\gestion\GestionCiclos;
use App\Livewire\coordinador\gestion\GestionCorrerias;

Route::get('/', function () {
    return view('auth.login');
})->name('login.index');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->rol === 'Supervisor' || $user->rol === 'Coordinador_Administrativo') {
            return redirect()->route('panel.tareas');
        }
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rutas Coordinador ---
    Route::get('/usuarios', Usuarios::class)
        ->name('users.index')
        ->middleware('role:Coordinador_Administrativo,SUPER');

    Route::get('/actividades', GestionActividades::class)
        ->name('actividades.index')
        ->middleware('role:Coordinador_Administrativo,SUPER');

    Route::get('/ciclos', GestionCiclos::class)
        ->name('ciclos.index')
        ->middleware('role:Coordinador_Administrativo,SUPER');

    Route::get('/correrias', GestionCorrerias::class)
        ->name('correrias.index')
        ->middleware('role:Coordinador_Administrativo,SUPER');

    Route::get('/gestion-unidades', GestionUnidadesTrabajo::class)
        ->name('unidades.index')
        ->middleware('role:Supervisor,Coordinador_Administrativo,SUPER');

    Route::get('/tareas/asignar', AsignacionTareas::class)
        ->name('tasks.assign')
        ->middleware('role:Coordinador_Administrativo,Supervisor,SUPER');

    // --- Rutas Supervisor ---
    Route::get('/panel-tareas', PanelTareas::class)
        ->name('panel.tareas')
        ->middleware('role:Supervisor,Coordinador_Administrativo,SUPER');

    Route::get('/supervisor/tarea/{tarea}', GestionarTarea::class)
        ->name('supervisor.tarea.gestionar')
        ->middleware('role:Supervisor,Coordinador_Administrativo,SUPER');

     Route::get('importacion',Importacion::class)
        ->name('importacion.index')
        ->middleware('role:Coordinador_Administrativo');

    
});
