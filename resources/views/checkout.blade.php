@extends('layout')

@section('title', 'Checkout')

@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif
<div class="max-w-md mx-auto p-8 bg-white rounded shadow mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Checkout</h1>
    <form id="checkout-form" method="POST" action="{{ route('checkout.pagar') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold" for="payer_email">E-mail</label>
            <input type="email" name="payer_email" id="payer_email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="seu@email.com" required>
        </div>
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded text-lg transition">Pagar com Mercado Pago</button>
    </form>
    <div class="mt-6 text-center">
        <span class="text-lg font-semibold">Valor a ser cobrado:</span>
        <span class="text-2xl font-bold text-green-600 block">R$ 1,00</span>
    </div>
    <p class="mt-6 text-sm text-gray-500 text-center">Pagamento 100% seguro.</p>
</div>
@endsection 