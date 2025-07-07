<?php

use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\GoogleMobileAuthController;
use Illuminate\Support\Facades\Route;

// Rotas de locações
Route::apiResource('locacoes', LocacaoController::class);

// Rotas de despesas
Route::apiResource('despesas', DespesaController::class);

// Rota para calcular saldo de uma locação
Route::get('locacoes/{locacao}/saldo', [LocacaoController::class, 'saldo']);

Route::post('/auth/google-mobile', [GoogleMobileAuthController::class, 'login']); 