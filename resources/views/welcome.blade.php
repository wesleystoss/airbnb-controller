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
    </head>
    <body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="min-h-screen flex flex-col">
            <!-- Header Mobile-First -->
            <header class="w-full bg-white/80 backdrop-blur-md border-b border-gray-200/50 fixed top-0 left-0 z-50">
                <div class="max-w-md mx-auto flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 text-white">
                                <path fill="currentColor" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold bg-gradient-to-r from-[#FF385C] to-[#e11d48] bg-clip-text text-transparent">Airbnb Controle</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('locacoes.create') }}" class="flex items-center gap-1 px-3 py-2 rounded-full bg-gradient-to-r from-[#FF385C] to-[#e11d48] text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 btn-press">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline">Nova</span>
                            </a>
                            
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 text-gray-700 font-medium hover:bg-white transition-all duration-300 btn-press">
                                    <div class="w-6 h-6 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-2xl shadow-xl z-50">
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
            <main class="pt-20 max-w-md lg:max-w-4xl xl:max-w-6xl mx-auto px-4 lg:px-8 pb-20 flex-1">
                @auth
                    <!-- Dashboard para usuários logados -->
                    <div class="spacing-responsive-lg">
                        <!-- Boas-vindas -->
                        <div class="card fade-in">
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
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 slide-up" style="animation-delay: 0.1s;">
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

                            <a href="{{ route('profile.show') }}" class="card card-hover text-center lg:col-span-1">
                                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1 lg:mb-2 text-responsive-lg">Meu Perfil</h3>
                                <p class="text-xs lg:text-sm text-gray-500">Gerenciar conta</p>
                            </a>
                        </div>

                        <!-- Estatísticas Rápidas -->
                        <div class="card slide-up" style="animation-delay: 0.2s;">
                            <h3 class="font-semibold text-gray-800 mb-4 lg:mb-6 text-responsive-xl">Resumo</h3>
                            <div class="grid grid-cols-3 gap-4 lg:gap-6">
                                <div class="text-center">
                                    <div class="text-2xl lg:text-3xl font-bold text-[#FF385C]">{{ \App\Models\Locacao::where('user_id', Auth::id())->count() }}</div>
                                    <div class="text-xs lg:text-sm text-gray-500">Locações</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl lg:text-3xl font-bold text-green-600">{{ \App\Models\Locacao::where('user_id', Auth::id())->where('status', 'ativa')->count() }}</div>
                                    <div class="text-xs lg:text-sm text-gray-500">Ativas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl lg:text-3xl font-bold text-blue-600">{{ \App\Models\Despesa::where('user_id', Auth::id())->count() }}</div>
                                    <div class="text-xs lg:text-sm text-gray-500">Despesas</div>
                                </div>
                            </div>
                        </div>
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
            </main>
        </div>
        
        <!-- Footer Mobile -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-gray-200/50 z-40">
            <div class="max-w-md mx-auto px-4 py-3">
                <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-4 h-4">
                        <path fill="#FF385C" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                    </svg>
                    <span class="font-semibold text-[#FF385C]">Airbnb Controle</span>
                    <span>&copy; {{ date('Y') }}</span>
                </div>
                <div class="text-center mt-1 text-xs text-gray-400">
                    <span>Uso pessoal • Não afiliado ao Airbnb</span>
                </div>
            </div>
        </footer>
    </body>
</html>
