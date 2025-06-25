<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\GestionUnidadesTrabajo;
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

require __DIR__ . '/auth.php';

Route::middleware([
    'auth',
    'role:"Lider_de_Proyecto / Analista, Coordinador_Administrativo"

'
])->group(function () { // Proteger la ruta
    Route::get('/gestion-unidades', GestionUnidadesTrabajo::class)->name('gestion.unidades');
});