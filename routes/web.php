<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocacaoWebController;
use App\Http\Controllers\DespesaWebController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;

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

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
