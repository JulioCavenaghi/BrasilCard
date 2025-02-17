<?php

use App\Http\Controllers\DepositoController;
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\TransferenciaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Home
    Route::get('/', [ExtratoController::class, 'showExtrato'])->name('home');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Depósito
    Route::get('/deposito', [DepositoController::class, 'view'])->name('deposito.index');
    Route::post('/deposito', [DepositoController::class, 'store'])->name('deposito.store');

    // Tranferência
    Route::get('/transferencia', [TransferenciaController::class, 'view'])->name('transferencia.index');
    Route::post('/transferencia', [TransferenciaController::class, 'store'])->name('transferencia.store');

    // Dados Sessão
    Route::get('/dadosSessao', [SessaoController::class, 'getDadosSessao']);

});

require __DIR__.'/auth.php';
