<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChamadoPublicoController;

Route::get('/chamado', [ChamadoPublicoController::class, 'create'])->name('chamado.create');
Route::post('/chamado', [ChamadoPublicoController::class, 'store'])->name('chamado.store');

Route::get('/', function () {
    return view('welcome');
});
