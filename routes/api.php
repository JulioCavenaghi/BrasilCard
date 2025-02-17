<?php

use App\Http\Controllers\EstornoController;
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\ResgateController;
use App\Http\Controllers\SaldoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Saldo
Route::get('/saldo/{id}', [SaldoController::class, 'getSaldo']);

// Extrato
Route::get('/extrato', [ExtratoController::class, 'showExtrato']);
Route::get('/extrato/{id}', [ExtratoController::class, 'showExtrato']);

// Resgate
Route::post('/resgate', [ResgateController::class, 'solicitarResgate']);

// Estorno
Route::post('/estorno', [EstornoController::class, 'solicitarEstorno']);

// Estorno
Route::post('/reembolso', [EstornoController::class, 'solicitarEstorno']);
