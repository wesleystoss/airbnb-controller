<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Airbnb Controle</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen p-4">
        <div class="min-h-screen flex flex-col">
            <!-- Header Mobile-First -->
            <header class="w-full bg-white/80 backdrop-blur-md border-b border-gray-200/50 fixed top-0 left-0 z-50 px-4">
                <div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto flex items-center justify-between py-3 lg:py-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 lg:gap-4">
                        <div class="w-8 h-8 lg:w-12 lg:h-12 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 lg:w-8 lg:h-8 text-white">
                                <path fill="currentColor" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                            </svg>
                        </div>
                        <span class="text-lg lg:text-2xl font-bold bg-gradient-to-r from-[#FF385C] to-[#e11d48] bg-clip-text text-transparent">Airbnb Controle</span>
                    </a>
                    
                    <div class="flex items-center gap-2 lg:gap-4">
                        @auth
                            <a href="{{ route('locacoes.create') }}" class="flex items-center gap-1 px-3 py-2 lg:px-6 lg:py-3 rounded-full bg-gradient-to-r from-[#FF385C] to-[#e11d48] text-white text-sm lg:text-base font-semibold shadow-lg hover:shadow-xl transition-all duration-300 btn-press">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lg:w-6 lg:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline">Nova</span>
                            </a>
                            
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 lg:px-4 lg:py-3 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 text-gray-700 font-medium hover:bg-white transition-all duration-300 btn-press">
                                    <div class="w-6 h-6 lg:w-10 lg:h-10 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs lg:text-lg font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden sm:inline text-sm lg:text-base">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 lg:w-6 lg:h-6 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-2xl shadow-xl z-50">
                                    <a href="{{ route('imoveis.index') }}" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50/80 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0 0H7a2 2 0 01-2-2v-5a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2h-5z" />
                                        </svg>
                                        Imóveis
                                    </a>
                                    <a href="{{ route('calendar.index') }}" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50/80 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Calendário
                                    </a>
                                    <a href="{{ route('locacoes.index') }}" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50/80 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        Minhas Locações
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50/80 transition-colors duration-200 rounded-t-2xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Meu Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-[#FF385C] hover:bg-red-50/80 transition-colors duration-200 rounded-b-2xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 text-gray-700 text-sm font-medium hover:bg-white transition-all duration-300 btn-press">Entrar</a>
                        @endauth
                    </div>
                </div>
            </header>
            
            <!-- Conteúdo Principal -->
            <main class="pt-3 lg:pt-10 pb-20 flex-1 mt-16 lg:mt-20 mb-16 lg:mb-16">
                <div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto" x-data="{ show: false }" x-init="setTimeout(() => show = true, 10)">
                @auth
                    <!-- Dashboard para usuários logados -->
                    <div class="spacing-responsive-lg" x-show="show" x-transition.opacity.duration.700ms>
                        <!-- Boas-vindas -->
                        <div class="card" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                            <div class="flex items-center gap-4 lg:gap-6">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-full flex items-center justify-center">
                                    <span class="text-white text-lg lg:text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h1 class="text-xl lg:text-2xl font-bold text-gradient">Olá, {{ Auth::user()->name }}!</h1>
                                    <p class="text-gray-500 text-sm lg:text-base">Bem-vindo ao seu painel de controle</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cards de Ação -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.1s;">
                            <a href="{{ route('locacoes.create') }}" class="card card-hover text-center">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1 lg:mb-2 text-responsive-lg">Nova Locação</h3>
                                <p class="text-xs lg:text-sm text-gray-500">Adicionar nova locação</p>
                            </a>

                            <a href="{{ route('locacoes.index') }}" class="card card-hover text-center">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1 lg:mb-2 text-responsive-lg">Minhas Locações</h3>
                                <p class="text-xs lg:text-sm text-gray-500">Ver todas as locações</p>
                            </a>

                            <a href="{{ route('calendar.index') }}" class="card card-hover text-center">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1 lg:mb-2 text-responsive-lg">Calendário</h3>
                                <p class="text-xs lg:text-sm text-gray-500">Ver calendário do Airbnb</p>
                            </a>

                            <a href="{{ route('profile.show') }}" class="card card-hover text-center">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1 lg:mb-2 text-responsive-lg">Meu Perfil</h3>
                                <p class="text-xs lg:text-sm text-gray-500">Gerenciar conta</p>
                            </a>
                        </div>

                        <!-- Tabs de Análise -->
                        <div class="card" x-data="{ activeTab: 'resumo' }" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.2s;">
                            <!-- Navegação das Tabs -->
                            <div class="flex border-b border-gray-200 mb-6">
                                <button @click="activeTab = 'resumo'" 
                                        :class="{ 'text-[#FF385C] border-[#FF385C]': activeTab === 'resumo', 'text-gray-500 border-transparent': activeTab !== 'resumo' }"
                                        class="flex-1 py-3 px-4 text-sm font-medium border-b-2 transition-all duration-200 hover:text-[#FF385C]">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Resumo Executivo
                                    </div>
                                </button>
                                <button @click="activeTab = 'grafico'" 
                                        :class="{ 'text-[#FF385C] border-[#FF385C]': activeTab === 'grafico', 'text-gray-500 border-transparent': activeTab !== 'grafico' }"
                                        class="flex-1 py-3 px-4 text-sm font-medium border-b-2 transition-all duration-200 hover:text-[#FF385C]">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                        </svg>
                                        Comparativo Mensal
                                    </div>
                                </button>
                            </div>

                            <!-- Conteúdo da Tab Resumo Executivo -->
                            <div x-show="activeTab === 'resumo'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                <div class="space-y-6">
                                    <!-- Estatísticas Principais -->
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-blue-600 font-medium">Total Locações</p>
                                                    <p class="text-xl font-bold text-blue-900">{{ $totalLocacoes ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-green-600 font-medium">Receita Total</p>
                                                    <p class="text-xl font-bold text-green-900">R$ {{ number_format($totalReceitas ?? 0, 2, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 4l2 2 4-4m4 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-red-600 font-medium">Despesas</p>
                                                    <p class="text-xl font-bold text-red-900">R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-purple-600 font-medium">Lucro Total</p>
                                                    <p class="text-xl font-bold {{ ($lucroTotal ?? 0) >= 0 ? 'text-purple-900' : 'text-red-600' }}">R$ {{ number_format($lucroTotal ?? 0, 2, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Análises Essenciais -->
                                    <div class="space-y-6">
                                        <!-- KPIs Principais -->
                                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                            <!-- Melhor Mês -->
                                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200 relative" x-data="{ tooltip: false }">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-xs text-green-700 font-medium">Melhor Mês</span>
                                                            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="text-green-600 hover:text-green-800 transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-lg font-bold text-green-900">R$ {{ number_format($analises['melhor_mes']['lucro'] ?? 0, 2, ',', '.') }}</div>
                                                        <div class="text-xs text-green-600 font-semibold">{{ $analises['melhor_mes']['mes'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <!-- Tooltip -->
                                                <div x-show="tooltip" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg z-50 whitespace-nowrap">
                                                    Mês com maior receita líquida (locações + co-anfitrião - despesas)
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Pior Mês -->
                                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200 relative" x-data="{ tooltip: false }">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-xs text-red-700 font-medium">Pior Mês</span>
                                                            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="text-red-600 hover:text-red-800 transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-lg font-bold text-red-900">R$ {{ number_format($analises['pior_mes']['lucro'] ?? 0, 2, ',', '.') }}</div>
                                                        <div class="text-xs text-red-600 font-semibold">{{ $analises['pior_mes']['mes'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <!-- Tooltip -->
                                                <div x-show="tooltip" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg z-50 whitespace-nowrap">
                                                    Mês com menor receita líquida (locações + co-anfitrião - despesas)
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Tendência -->
                                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 relative" x-data="{ tooltip: false }">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-xs text-blue-700 font-medium">Tendência</span>
                                                            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="text-blue-600 hover:text-blue-800 transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-lg font-bold {{ ($analises['tendencia']['percentual'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($analises['tendencia']['percentual'] ?? 0, 0) }}%</div>
                                                        <div class="text-xs text-blue-600 font-semibold">
                                                            @if(isset($analises['tendencia']['direcao']))
                                                                @if($analises['tendencia']['direcao'] === 'crescimento')📈@elseif($analises['tendencia']['direcao'] === 'queda')📉@else➡️@endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tooltip -->
                                                <div x-show="tooltip" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg z-50 whitespace-nowrap">
                                                    Comparação entre os últimos 2 meses (crescimento ou queda)
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Rentabilidade -->
                                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 relative" x-data="{ tooltip: false }">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-xs text-purple-700 font-medium">Rentabilidade</span>
                                                            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="text-purple-600 hover:text-purple-800 transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-lg font-bold text-purple-900">{{ number_format($analises['rentabilidade']['total'] ?? 0, 0) }}%</div>
                                                        <div class="text-xs text-purple-600 font-semibold">Total</div>
                                                    </div>
                                                </div>
                                                <!-- Tooltip -->
                                                <div x-show="tooltip" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg z-50 whitespace-nowrap">
                                                    Percentual de lucro sobre receita total (últimos 6 meses)
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Resumo Rápido -->
                                        <div class="bg-gray-50 rounded-xl p-6">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Resumo dos Últimos 6 Meses</h4>
                                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach(array_slice($ultimos6Meses ?? [], -6) as $mes => $dados)
                                                @php
                                                    $periodo = \Carbon\Carbon::createFromFormat('m/Y', $mes)->format('Y-m');
                                                @endphp
                                                <a href="{{ route('locacoes.index', ['periodo' => $periodo]) }}" class="bg-white rounded-lg p-4 border border-gray-200 transition-shadow hover:shadow-lg block">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="text-xs text-gray-500 font-medium">{{ $mes }}</div>
                                                    </div>
                                                    <div class="text-lg font-bold {{ $dados['lucro'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        R$ {{ number_format($dados['lucro'], 2, ',', '.') }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 font-semibold">
                                                        {{ $dados['quantidade'] }} locações
                                                    </div>
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conteúdo da Tab Gráfico Comparativo -->
                            <div x-show="activeTab === 'grafico'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                <div class="space-y-6">
                                    <!-- Gráfico -->
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                            </svg>
                                            <h4 class="font-bold text-base text-[#222]">Lucro Mensal</h4>
                                        </div>
                                        <div class="overflow-x-auto w-full">
                                            <canvas id="lucroChart" height="300" class="min-w-[550px] min-h-[440px]"></canvas>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-lg p-4 shadow-sm">
                                                <span class="text-2xl">📅</span>
                                                <div>
                                                    <div class="text-xs text-gray-500 font-semibold">Resumo do mês atual ({{ now()->format('m/Y') }}):</div>
                                                    <div class="text-xl font-bold text-blue-900">R$ {{ number_format($resumoMensal[now()->format('m/Y')] ?? 0, 2, ',', '.') }}</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-lg p-4 shadow-sm">
                                                <span class="text-2xl">📈</span>
                                                <div>
                                                    <div class="text-xs text-gray-500 font-semibold">Resumo do ano ({{ now()->format('Y') }}):</div>
                                                    <div class="text-xl font-bold text-green-900">R$ {{ number_format($resumoAnual[now()->format('Y')] ?? 0, 2, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Script para o gráfico -->
                        @if(isset($locacoesMensal) && isset($coanfitriaoMensal) && isset($despesasMensal) && isset($resumoMensal))
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
                        <script>
                            const ctx = document.getElementById('lucroChart').getContext('2d');
                            const isMobile = window.innerWidth < 640;
                            const lucroChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: {!! json_encode(array_keys($resumoMensal)) !!},
                                    datasets: [
                                        {
                                            label: 'Locações (R$)',
                                            data: {!! json_encode(array_values($locacoesMensal)) !!},
                                            backgroundColor: '#a78bfa',
                                            borderColor: '#7c3aed',
                                            borderWidth: 2,
                                            borderRadius: 8,
                                            maxBarThickness: 40
                                        },
                                        {
                                            label: 'Co-anfitrião (R$)',
                                            data: {!! json_encode(array_values($coanfitriaoMensal)) !!},
                                            backgroundColor: '#ff385c',
                                            borderColor: '#ff385c',
                                            borderWidth: 2,
                                            borderRadius: 8,
                                            maxBarThickness: 40
                                        },
                                        {
                                            label: 'Despesas (R$)',
                                            data: {!! json_encode(array_values($despesasMensal)) !!},
                                            backgroundColor: '#3b82f6',
                                            borderColor: '#1d4ed8',
                                            borderWidth: 2,
                                            borderRadius: 8,
                                            maxBarThickness: 40
                                        },
                                        {
                                            label: 'Saldo Final (R$)',
                                            data: {!! json_encode(array_values($resumoMensal)) !!},
                                            backgroundColor: '#22c55e',
                                            borderColor: '#16a34a',
                                            borderWidth: 2,
                                            borderRadius: 8,
                                            maxBarThickness: 40
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: true, labels: { font: { size: isMobile ? 12 : 14 } } },
                                        title: { display: false },
                                        datalabels: {
                                            anchor: 'end',
                                            align: 'end',
                                            offset: 12,
                                            color: '#222',
                                            font: { weight: 'bold', size: isMobile ? 10 : 12 },
                                            formatter: function(value) {
                                                return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                                            }
                                        }
                                    },
                                    layout: {
                                        padding: {
                                            top: 32
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                display: false
                                            },
                                            grid: {
                                                display: false
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                display: true,
                                                font: { size: isMobile ? 10 : 12 },
                                                maxRotation: isMobile ? 45 : 0,
                                                minRotation: isMobile ? 45 : 0
                                            }
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels]
                            });
                        </script>
                        @endif
                    </div>
                @else
                    <!-- Landing Page para visitantes -->
                    <div class="spacing-responsive-lg">
                        <!-- Hero Section -->
                        <div class="text-center pt-8 lg:pt-12 fade-in">
                            <div class="w-24 h-24 lg:w-32 lg:h-32 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-3xl flex items-center justify-center mx-auto mb-6 lg:mb-8 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-12 h-12 lg:w-16 lg:h-16 text-white">
                                    <path fill="currentColor" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-gradient mb-4 lg:mb-6">Airbnb Controle</h1>
                            <p class="text-gray-600 mb-8 lg:mb-12 text-responsive-lg">Gerencie suas locações do Airbnb de forma simples e eficiente</p>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4 lg:space-y-6 slide-up" style="animation-delay: 0.1s;">
                            <div class="card">
                                <div class="flex items-center gap-4 lg:gap-6">
                                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center">
                                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-responsive-xl">Controle Total</h3>
                                        <p class="text-sm lg:text-base text-gray-500">Gerencie todas as suas locações em um só lugar</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="flex items-center gap-4 lg:gap-6">
                                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-responsive-xl">Controle Financeiro</h3>
                                        <p class="text-sm lg:text-base text-gray-500">Acompanhe receitas e despesas facilmente</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="flex items-center gap-4 lg:gap-6">
                                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-responsive-xl">Rápido e Simples</h3>
                                        <p class="text-sm lg:text-base text-gray-500">Interface intuitiva para uso diário</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="text-center slide-up" style="animation-delay: 0.2s;">
                            <a href="{{ route('register') }}" class="btn-primary inline-block">
                                Começar Agora
                            </a>
                            <p class="text-sm lg:text-base text-gray-500 mt-4 lg:mt-6">Já tem uma conta? 
                                <a href="{{ route('login') }}" class="text-[#FF385C] font-medium">Entrar</a>
                            </p>
                        </div>
                    </div>
                @endauth
                </div>
            </main>
        </div>
        
        <!-- Footer Mobile -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-gray-200/50 z-40">
            <div class="max-w-md lg:max-w-4xl xl:max-w-6xl mx-auto px-4 lg:px-8 py-3 lg:py-4">
                <div class="flex items-center justify-center gap-2 lg:gap-4 text-xs lg:text-sm text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-4 h-4 lg:w-6 lg:h-6">
                        <path fill="#FF385C" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                    </svg>
                    <span class="font-semibold text-[#FF385C]">Airbnb Controle</span>
                    <span>&copy; {{ date('Y') }}</span>
                </div>
                <div class="text-center mt-1 text-xs lg:text-sm text-gray-400">
                    <span>Uso pessoal • Não afiliado ao Airbnb</span>
                </div>
            </div>
        </footer>
    </body>
</html>
