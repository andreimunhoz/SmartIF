<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ROTAS DO SISTEMA DE CHAMADOS E ESTOQUE ---
    
    // Rotas para /chamados, /chamados/create, /chamados/{id}, etc.
    Route::resource('chamados', ChamadoController::class); 
    
    // Rotas para /materiais, /materiais/create, /materiais/{id}, etc.
    Route::resource('materiais', MaterialController::class)
    ->parameter('materiais', 'material');
});

require __DIR__.'/auth.php';