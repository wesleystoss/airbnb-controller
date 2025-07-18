@extends('layout')

@section('title', 'Assinatura')

@section('content')
<div class="min-h-screen py-10 px-2">
    <div class="max-w-xl mx-auto">
        {{-- Mensagens de sistema --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 text-red-700 border border-red-200 rounded">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 text-green-700 border border-green-200 rounded">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="mb-4 p-4 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded">{{ session('warning') }}</div>
        @endif
        @if(request('success') && auth()->check() && auth()->user()->assinaturaAtiva)
            <div class="mb-4 p-4 bg-green-50 text-green-700 border border-green-200 rounded">Assinatura ativada com sucesso! Você já pode usar todas as funcionalidades.</div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 mt-6">
            {{-- Selo de segurança e recorrência --}}
            <div class="flex justify-end gap-2 mb-2">
                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded border border-gray-200">Pagamento seguro</span>
                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded border border-gray-200">Recorrente</span>
            </div>

            @auth
                @php $assinaturaAtiva = auth()->user()->assinaturaAtiva; @endphp
                @if($assinaturaAtiva && $assinaturaAtiva->status === 'ativa')
                    <div class="mb-8 p-6 bg-white border border-green-200 rounded-xl text-center">
                        <h2 class="text-xl font-semibold text-green-800 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Assinatura ativa
                        </h2>
                        <p class="text-green-700">Válida até <strong>{{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</strong></p>
                        <p class="text-xs text-green-600 mt-1">Próxima cobrança: {{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</p>
                    </div>
                    <form action="{{ route('assinatura.cancelar') }}" method="POST" class="mb-6" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?')">
                        @csrf
                        <button type="submit" class="w-full bg-gray-200 hover:bg-red-100 text-red-700 font-semibold py-3 rounded-lg border border-red-200 transition">Cancelar assinatura</button>
                    </form>
                @else
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold mb-2 text-gray-900">Assinatura Airbnb Controle</h1>
                        <p class="text-gray-600 mb-4">Tenha controle total dos seus imóveis, locações e finanças em um só lugar.</p>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-green-700">R$ 39,90</span>
                            <span class="text-gray-500">/mês</span>
                        </div>
                        <a href="{{ route('checkout.pagar') }}" class="inline-block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg text-lg transition">Assinar agora</a>
                        <div class="text-xs text-gray-400 mt-1">Cobrança automática, cancele quando quiser</div>
                    </div>
                    <ul class="mb-8 space-y-3 text-gray-700">
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Cadastro e gerenciamento ilimitado de imóveis</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Controle financeiro detalhado</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Agenda de locações integrada</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Compartilhamento de imóveis</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg> Relatórios automáticos</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Suporte humano e rápido</li>
                    </ul>
                    <div class="mb-6 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs border border-gray-200">7 dias de garantia: teste sem risco</div>
                        <div class="text-xs text-gray-400 mt-1">Cancele em até 7 dias e receba 100% do seu dinheiro de volta.</div>
                    </div>
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700">
                            <span class="block font-medium text-gray-900 mb-1">“Facilitou minha vida, nunca mais perdi controle das locações.”</span>
                            <span class="text-xs text-gray-500">Ana Paula, SP</span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700">
                            <span class="block font-medium text-gray-900 mb-1">“O suporte é incrível, sempre me ajudam rápido.”</span>
                            <span class="text-xs text-gray-500">João Pedro, RJ</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-800 mb-2">Perguntas frequentes</h3>
                        <div class="space-y-2">
                            <details class="bg-gray-50 rounded p-2 border border-gray-100">
                                <summary class="font-medium text-gray-700 cursor-pointer">Posso cancelar quando quiser?</summary>
                                <p class="text-gray-500 text-sm mt-1">Sim! O cancelamento é imediato e sem burocracia, direto pelo painel.</p>
                            </details>
                            <details class="bg-gray-50 rounded p-2 border border-gray-100">
                                <summary class="font-medium text-gray-700 cursor-pointer">O pagamento é seguro?</summary>
                                <p class="text-gray-500 text-sm mt-1">Totalmente seguro, processado pelo Mercado Pago.</p>
                            </details>
                            <details class="bg-gray-50 rounded p-2 border border-gray-100">
                                <summary class="font-medium text-gray-700 cursor-pointer">Preciso instalar algo?</summary>
                                <p class="text-gray-500 text-sm mt-1">Não! Tudo funciona direto do navegador, sem instalações.</p>
                            </details>
                            <details class="bg-gray-50 rounded p-2 border border-gray-100">
                                <summary class="font-medium text-gray-700 cursor-pointer">E se eu tiver dúvidas?</summary>
                                <p class="text-gray-500 text-sm mt-1">Conte com nosso suporte humano, rápido e eficiente.</p>
                            </details>
                        </div>
                    </div>
                @endif
            @endauth
            @guest
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2 text-gray-900">Assinatura Airbnb Controle</h1>
                    <p class="text-gray-600 mb-4">Tenha controle total dos seus imóveis, locações e finanças em um só lugar.</p>
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-green-700">R$ 39,90</span>
                        <span class="text-gray-500">/mês</span>
                    </div>
                    <a href="{{ route('login') }}" class="inline-block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg text-lg transition">Fazer login para assinar</a>
                    <div class="text-xs text-gray-400 mt-1">Cobrança automática, cancele quando quiser</div>
                </div>
                <ul class="mb-8 space-y-3 text-gray-700">
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Cadastro e gerenciamento ilimitado de imóveis</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Controle financeiro detalhado</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Agenda de locações integrada</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Compartilhamento de imóveis</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg> Relatórios automáticos</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Suporte humano e rápido</li>
                </ul>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs border border-gray-200">7 dias de garantia: teste sem risco</div>
                    <div class="text-xs text-gray-400 mt-1">Cancele em até 7 dias e receba 100% do seu dinheiro de volta.</div>
                </div>
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700">
                        <span class="block font-medium text-gray-900 mb-1">“Facilitou minha vida, nunca mais perdi controle das locações.”</span>
                        <span class="text-xs text-gray-500">Ana Paula, SP</span>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700">
                        <span class="block font-medium text-gray-900 mb-1">“O suporte é incrível, sempre me ajudam rápido.”</span>
                        <span class="text-xs text-gray-500">João Pedro, RJ</span>
                    </div>
                </div>
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Perguntas frequentes</h3>
                    <div class="space-y-2">
                        <details class="bg-gray-50 rounded p-2 border border-gray-100">
                            <summary class="font-medium text-gray-700 cursor-pointer">Posso cancelar quando quiser?</summary>
                            <p class="text-gray-500 text-sm mt-1">Sim! O cancelamento é imediato e sem burocracia, direto pelo painel.</p>
                        </details>
                        <details class="bg-gray-50 rounded p-2 border border-gray-100">
                            <summary class="font-medium text-gray-700 cursor-pointer">O pagamento é seguro?</summary>
                            <p class="text-gray-500 text-sm mt-1">Totalmente seguro, processado pelo Mercado Pago.</p>
                        </details>
                        <details class="bg-gray-50 rounded p-2 border border-gray-100">
                            <summary class="font-medium text-gray-700 cursor-pointer">Preciso instalar algo?</summary>
                            <p class="text-gray-500 text-sm mt-1">Não! Tudo funciona direto do navegador, sem instalações.</p>
                        </details>
                        <details class="bg-gray-50 rounded p-2 border border-gray-100">
                            <summary class="font-medium text-gray-700 cursor-pointer">E se eu tiver dúvidas?</summary>
                            <p class="text-gray-500 text-sm mt-1">Conte com nosso suporte humano, rápido e eficiente.</p>
                        </details>
                    </div>
                </div>
            @endguest
        </div>
        <p class="mt-8 text-xs text-gray-400 text-center">Cancele quando quiser, sem burocracia. Suporte humano e rápido. Satisfação garantida ou seu dinheiro de volta em até 7 dias.</p>
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
</div>
@endsection 