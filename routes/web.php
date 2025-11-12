<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\MaterialController;

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

    // --- ROTAS DO SISTEMA DE CHAMADOS E ESTOQUE ---
    
    // Rotas para /chamados, /chamados/create, /chamados/{id}, etc.
    Route::resource('chamados', ChamadoController::class); // <-- ADICIONE AQUI
    
    // Rotas para /materiais, /materiais/create, /materiais/{id}, etc.
    Route::resource('materiais', MaterialController::class); // <-- ADICIONE AQUI
});

require __DIR__.'/auth.php';