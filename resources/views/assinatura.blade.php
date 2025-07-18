@extends('layout')

@section('title', 'Assinatura')

@section('content')
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif
<div class="max-w-2xl mx-auto p-8 bg-white rounded shadow mt-10">
    <h1 class="text-3xl font-bold mb-4 text-center">Assine e aproveite todos os benefícios!</h1>
    <p class="text-lg mb-6 text-center">Por apenas <span class="text-2xl font-bold text-green-600">R$ 39,90/mês</span></p>
    <ul class="mb-8 space-y-3">
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Cadastro e gerenciamento ilimitado de imóveis</li>
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Controle de despesas e receitas detalhado</li>
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Agenda de locações integrada ao calendário</li>
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Compartilhamento de imóveis com outros usuários</li>
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Relatórios financeiros automáticos</li>
        <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span> Suporte prioritário</li>
    </ul>
    @auth
    <a href="{{ route('checkout.pagar') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded text-lg transition block text-center">
        Comprar agora
    </a>
    @endauth
    <p class="mt-6 text-sm text-gray-500 text-center">Cancele quando quiser, sem burocracia.</p>
</div>
@endsection 