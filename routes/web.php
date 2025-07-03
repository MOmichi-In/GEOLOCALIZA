<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\EditarLivewire;
use App\Livewire\GestionUnidadesTrabajo;
use App\Livewire\Supervisor\Dashboard;
use App\Livewire\TaskAssignment;
use App\Livewire\UserLivewire;
use App\Livewire\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');
//INICIO
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//PERFIL 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//MODULO 1
Route::get('/tareas/asignar', TaskAssignment::class)
    ->middleware('role:Coordinador_Administrativo') // Ajusta el rol si es diferente
    ->name('tasks.assign');
//SUPERVISOR DASHBOARD
Route::get('/supervisor/dashboard', Dashboard::class)
    ->middleware('role:Coordinador_Administrativo')
    ->name('supervisor.dashboard');

//AUTH
require __DIR__ . '/auth.php';

Route::get('/users/index', Usuarios::class)
->name('users');



Route::middleware([
    'auth',
    'role:Coordinador_Administrativo'
])->group(function () { // Proteger la ruta
    Route::get('/gestion-unidades', GestionUnidadesTrabajo::class)->name('unidades.index');
});

// Route::get('/check', function () {
//     return 'Estás autenticado como ' . auth()->user()->name;
// })->middleware('auth');

// Route::get('/debug', function () {
//     return [
//         'auth' => auth()->check(),
//         'user' => auth()->user(),
//     ];
// });
