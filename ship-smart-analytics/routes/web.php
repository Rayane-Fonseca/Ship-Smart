<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacoteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Rota Principal Pública (Abre a welcome.blade.php diretamente)
Route::get('/', fn() => view('welcome'))->name('welcome');

// 2. Rotas Protegidas (Dashboard e Pacotes)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pacotes', PacoteController::class);
});

// 3. Rotas de Perfil (Apenas Autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas de autenticação padrão do Laravel (Login, Registro, etc.)
require __DIR__.'/auth.php';