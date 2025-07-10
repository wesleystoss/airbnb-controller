<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Airbnb Controle</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="32x32">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#FF385C">
    <meta name="msapplication-TileColor" content="#FF385C">
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        html, body { 
            overflow-x: hidden; 
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #FF385C;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #e11d48;
        }
        
        /* Animações suaves */
        .app-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Efeito de pressão nos botões */
        .btn-press:active {
            transform: scale(0.95);
        }
        
        /* Gradiente de fundo */
        .app-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Sombra de cartão */
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Efeito de glassmorphism */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
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
        <main class="pt-[80px] lg:pt-[130px] w-full pb-20 flex-1 mb-4">
            <div class="px-4 lg:px-12">
            <!-- Notificações -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50/80 backdrop-blur-sm border border-green-200/50 rounded-2xl text-green-800 text-sm shadow-lg max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50/80 backdrop-blur-sm border border-red-200/50 rounded-2xl text-red-800 text-sm shadow-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <ul class="space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
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