@extends('layout')

@section('title', 'Assinatura')

@section('content')
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
        {{ session('warning') }}
    </div>
@endif

@if(request('success') && auth()->check() && auth()->user()->assinaturaAtiva)
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        ✅ Assinatura ativada com sucesso! Você já pode usar todas as funcionalidades.
    </div>
@endif

<div class="max-w-2xl mx-auto p-8 bg-white rounded shadow mt-10">
    @auth
        @php
            $assinaturaAtiva = auth()->user()->assinaturaAtiva;
        @endphp
        
        @if($assinaturaAtiva && $assinaturaAtiva->status === 'ativa')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                <h2 class="text-xl font-semibold text-green-800 mb-2">✅ Assinatura Ativa</h2>
                <p class="text-green-700">Sua assinatura está ativa até <strong>{{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</strong></p>
                <p class="text-sm text-green-600 mt-1">Próxima cobrança: {{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</p>
            </div>
            
            <form action="{{ route('assinatura.cancelar') }}" method="POST" class="mb-6" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?')">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded text-lg transition">
                    Cancelar Assinatura
                </button>
            </form>
        @else
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
            
            <a href="{{ route('checkout.pagar') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded text-lg transition block text-center">
                Comprar agora
            </a>
        @endif
    @else
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
        
        <a href="{{ route('login') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded text-lg transition block text-center">
            Fazer login para assinar
        </a>
    @endauth
    
    <p class="mt-6 text-sm text-gray-500 text-center">Cancele quando quiser, sem burocracia.</p>
    
    @if(session('url.intended') && auth()->check() && auth()->user()->assinaturaAtiva)
        <div class="mt-4 text-center">
            <a href="{{ session('url.intended') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Voltar para onde você estava
            </a>
        </div>
        <script>
            // Redireciona automaticamente após 3 segundos
            setTimeout(function() {
                window.location.href = '{{ session('url.intended') }}';
            }, 3000);
        </script>
    @endif
</div>
@endsection 