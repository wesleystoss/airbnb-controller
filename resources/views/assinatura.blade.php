@extends('layout')

@section('title', 'Assinatura')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen py-0 px-0">
    <div class="max-w-3xl mx-auto">
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

        @auth
            @php $assinaturaAtiva = auth()->user()->assinaturaAtiva; @endphp
            @if($assinaturaAtiva && $assinaturaAtiva->status === 'ativa')
                <div class="max-w-lg mx-auto mt-10 bg-white border border-green-200 rounded-2xl shadow-lg p-8 text-center">
                    <h2 class="text-2xl font-bold text-green-800 mb-2 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Assinatura ativa
                    </h2>
                    <p class="text-green-700">Válida até <strong>{{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</strong></p>
                    <p class="text-xs text-green-600 mt-1">Próxima cobrança: {{ \Carbon\Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y') }}</p>
                    <form action="{{ route('assinatura.cancelar') }}" method="POST" class="mt-6" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?')">
                        @csrf
                        <button type="submit" class="w-full bg-gray-200 hover:bg-red-100 text-red-700 font-semibold py-3 rounded-lg border border-red-200 transition">Cancelar assinatura</button>
                    </form>
                </div>
            @else
                {{-- CARD PRINCIPAL AGRUPANDO TUDO (incluindo o Hero) --}}
                <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
                    {{-- HERO --}}
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-xs rounded-full border border-gray-200 mb-3">Pagamento seguro via Mercado Pago</span>
                        <h1 class="text-4xl lg:text-5xl font-extrabold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-[#FF385C] to-[#e11d48]">Chega de planilhas, confusão e tempo perdido</h1>
                        <p class="text-lg text-gray-700 mb-6">Tenha controle total dos seus imóveis, locações e finanças em um só lugar.</p>
                        <div class="flex flex-col items-center gap-2 mb-4">
                            <span class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#FF385C] to-[#e11d48] text-white text-3xl font-bold rounded-full shadow">R$ 39,90/mês</span>
                            <span class="text-xs text-gray-500">Cobrança automática, cancele quando quiser</span>
                        </div>
                        <a href="{{ route('checkout.pagar') }}" class="inline-block w-full md:w-auto bg-gradient-to-r from-[#FF385C] to-[#e11d48] hover:from-[#e11d48] hover:to-[#FF385C] text-white font-bold py-4 px-8 rounded-full text-xl shadow-lg transition mb-2">Quero Assinar Agora</a>
                    </div>
                    {{-- 2. DOR/PROBLEMA --}}
                    <div class="border-t border-gray-100 pt-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Você se identifica?</h2>
                        <ul class="text-gray-600 text-base space-y-1 mb-2">
                            <li>• Esquece de cobrar hóspedes ou perde prazos?</li>
                            <li>• Não sabe exatamente quanto lucrou no mês?</li>
                            <li>• Tem dificuldade para compartilhar informações com sócios?</li>
                            <li>• Gasta tempo demais organizando tudo manualmente?</li>
                        </ul>
                        <span class="text-sm text-gray-400">Você não está sozinho. A maioria dos anfitriões passa por isso.</span>
                    </div>
                    {{-- 3. SOLUÇÃO --}}
                    <div class="border-t border-gray-100 mt-8 pt-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">O Airbnb Controle resolve tudo para você</h2>
                        <p class="text-gray-600 mb-6">Automatize sua gestão, aumente seus lucros e tenha tranquilidade.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-[#FF385C] rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Gestão Ilimitada</h3>
                                    <p class="text-gray-600 text-sm">Cadastre e gerencie quantos imóveis quiser, sem limites.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-blue-500 rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Controle Financeiro</h3>
                                    <p class="text-gray-600 text-sm">Despesas, receitas, relatórios e lucros em tempo real.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-orange-500 rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Agenda Integrada</h3>
                                    <p class="text-gray-600 text-sm">Calendário de locações sincronizado com o Airbnb.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-purple-500 rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Compartilhamento Fácil</h3>
                                    <p class="text-gray-600 text-sm">Compartilhe imóveis com sócios, familiares ou coanfitriões.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-pink-500 rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Relatórios Automáticos</h3>
                                    <p class="text-gray-600 text-sm">Receba relatórios financeiros prontos para análise.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-100 text-yellow-500 rounded-full p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                <div>
                                    <h3 class="font-bold text-lg">Suporte Humano</h3>
                                    <p class="text-gray-600 text-sm">Atendimento rápido e humano, sem robôs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 4. COMO FUNCIONA --}}
                    <div class="border-t border-gray-100 mt-8 pt-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Como funciona?</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 mb-2">
                                    <span class="text-2xl font-bold text-[#FF385C]">1</span>
                                </div>
                                <span class="font-semibold text-gray-700">Assine em 1 minuto</span>
                                <span class="text-xs text-gray-500">Pagamento rápido e seguro</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 mb-2">
                                    <span class="text-2xl font-bold text-blue-500">2</span>
                                </div>
                                <span class="font-semibold text-gray-700">Use sem limites</span>
                                <span class="text-xs text-gray-500">Acesse todos os recursos</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 mb-2">
                                    <span class="text-2xl font-bold text-green-600">3</span>
                                </div>
                                <span class="font-semibold text-gray-700">Cancele quando quiser</span>
                                <span class="text-xs text-gray-500">Sem burocracia, sem pegadinhas</span>
                            </div>
                        </div>
                    </div>
                    {{-- 5. DEPOIMENTOS --}}
                    <div class="border-t border-gray-100 mt-8 pt-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">O que dizem nossos clientes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700 shadow-sm">
                                <span class="block font-medium text-gray-900 mb-1">“Facilitou minha vida, nunca mais perdi controle das locações.”</span>
                                <span class="text-xs text-gray-500">Ana Paula, SP</span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-700 shadow-sm">
                                <span class="block font-medium text-gray-900 mb-1">“O suporte é incrível, sempre me ajudam rápido.”</span>
                                <span class="text-xs text-gray-500">João Pedro, RJ</span>
                            </div>
                        </div>
                    </div>
                    {{-- 6. FAQ --}}
                    <div class="border-t border-gray-100 mt-8 pt-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Perguntas frequentes</h2>
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
                </div>
                {{-- GARANTIA/SEGURANÇA --}}
                <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm border border-gray-200 font-semibold mb-2">7 dias de garantia: teste sem risco</div>
                    <div class="text-xs text-gray-400">Cancele em até 7 dias e receba 100% do seu dinheiro de volta.</div>
                </div>
                {{-- CHAMADA FINAL --}}
                <section class="py-8 px-4 text-center">
                    <div class="max-w-2xl mx-auto">
                        <a href="{{ route('checkout.pagar') }}" class="inline-block w-full md:w-auto bg-gradient-to-r from-[#FF385C] to-[#e11d48] hover:from-[#e11d48] hover:to-[#FF385C] text-white font-bold py-4 px-8 rounded-full text-xl shadow-lg transition mb-2">Quero Assinar Agora</a>
                        <div class="text-xs text-gray-400 mt-1">Cobrança automática, cancele quando quiser</div>
                    </div>
                </section>
            @endif
        @endauth
        @guest
            {{-- Mesma landing page para visitantes --}}
            @php $assinaturaAtiva = null; @endphp
            @include('assinatura', ['assinaturaAtiva' => $assinaturaAtiva])
        @endguest
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