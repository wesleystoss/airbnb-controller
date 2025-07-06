<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocacaoWebController;
use App\Http\Controllers\DespesaWebController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LocacaoWebController::class, 'index'])->name('home');

// Locações
Route::get('/locacoes', [LocacaoWebController::class, 'index'])->name('locacoes.index');
Route::get('/locacoes/create', [LocacaoWebController::class, 'create'])->name('locacoes.create');
Route::post('/locacoes', [LocacaoWebController::class, 'store'])->name('locacoes.store');
Route::get('/locacoes/{locacao}', [LocacaoWebController::class, 'show'])->name('locacoes.show');
Route::get('/locacoes/{locacao}/edit', [LocacaoWebController::class, 'edit'])->name('locacoes.edit');
Route::put('/locacoes/{locacao}', [LocacaoWebController::class, 'update'])->name('locacoes.update');
Route::delete('/locacoes/{locacao}', [LocacaoWebController::class, 'destroy'])->name('locacoes.destroy');

// Despesas
Route::get('/locacoes/{locacao}/despesas/create', [DespesaWebController::class, 'create'])->name('despesas.create');
Route::post('/locacoes/{locacao}/despesas', [DespesaWebController::class, 'store'])->name('despesas.store');
Route::get('/despesas/{despesa}/edit', [DespesaWebController::class, 'edit'])->name('despesas.edit');
Route::put('/despesas/{despesa}', [DespesaWebController::class, 'update'])->name('despesas.update');
Route::delete('/despesas/{despesa}', [DespesaWebController::class, 'destroy'])->name('despesas.destroy');
