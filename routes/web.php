<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\GestionUnidadesTrabajo;
use App\Livewire\TaskAssignment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tareas/asignar', TaskAssignment::class)
    ->middleware('role:Coordinador_Administrativo') // Ajusta el rol si es diferente
    ->name('tasks.assign');

require __DIR__ . '/auth.php';

// Route::middleware([
//     'auth',
//     'role:Lider_de_Proyecto / Analista,Coordinador_Administrativo'
// ])->group(function () { // Proteger la ruta
//     Route::get('/gestion-unidades', GestionUnidadesTrabajo::class)->name('unidades.index');
// });

// Route::get('/check', function () {
//     return 'Estás autenticado como ' . auth()->user()->name;
// })->middleware('auth');

// Route::get('/debug', function () {
//     return [
//         'auth' => auth()->check(),
//         'user' => auth()->user(),
//     ];
// });
