<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocacaoWebController;
use App\Http\Controllers\DespesaWebController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\CheckoutController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Home pública para todos
Route::get('/', function () {
    return view('assinatura');
})->name('home');

// Painel protegido para logados com assinatura ativa
Route::get('/painel', [LocacaoWebController::class, 'home'])->name('painel')->middleware(['auth', 'assinatura.ativa']);

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/google/mobile', [App\Http\Controllers\Auth\GoogleController::class, 'mobileLogin'])->name('google.mobile');

// Rotas de perfil
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');
    Route::get('/checkout/pagar', [\App\Http\Controllers\CheckoutController::class, 'pagar'])->name('checkout.pagar');
});

// Rotas que precisam de assinatura ativa
Route::middleware(['auth', 'assinatura.ativa'])->group(function () {
    // Imóveis
    Route::resource('imoveis', ImovelController::class)
        ->parameters(['imoveis' => 'imovel'])
        ->except(['show']);
    Route::post('imoveis/{imovel}/compartilhar', [ImovelController::class, 'adicionarCompartilhamento'])->name('imoveis.compartilhamento.adicionar');
    Route::delete('imoveis/compartilhar/{compartilhamento}', [ImovelController::class, 'removerCompartilhamento'])->name('imoveis.compartilhamento.remover');
    
    // Calendário
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/{imovel}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::post('/calendar/{imovel}/update-ical', [CalendarController::class, 'updateIcalUrl'])->name('calendar.update-ical');
    Route::post('/calendar/{imovel}/sync', [CalendarController::class, 'syncCalendar'])->name('calendar.sync');

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
});

Route::get('/assinatura', function () {
    return view('assinatura');
})->name('assinatura');

Route::middleware(['auth'])->group(function () {
    Route::get('/assinatura/cancelar', [\App\Http\Controllers\CheckoutController::class, 'cancelar'])->name('assinatura.cancelar.form');
    Route::post('/assinatura/cancelar', [\App\Http\Controllers\CheckoutController::class, 'cancelar'])->name('assinatura.cancelar');
});

// Rota de teste para o webhook (apenas para debug)
Route::get('/webhook/mercadopago/test', function() {
    return response()->json(['status' => 'webhook endpoint is working'], 200);
})->name('webhook.mercadopago.test');
