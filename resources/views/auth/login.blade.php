<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Airbnb</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 lg:p-8 items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <!-- Header -->
            <header class="text-center mb-8">
                <a href="/" class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-16 h-16"><path fill="#FF385C" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/></svg>
                </a>
                <h2 class="text-lg font-bold text-[#FF385C] mb-2">Airbnb Controle</h2>
                <h1 class="text-2xl font-semibold mb-2">Entrar</h1>
                <p class="text-[#706f6c] text-sm">Bem-vindo de volta</p>
            </header>

            <!-- Login Form -->
            <div class="bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-lg p-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-white text-[#1b1b18] placeholder-[#706f6c] focus:outline-none focus:border-[#1b1b18] transition-colors"
                            placeholder="seu@email.com"
                        >
                        @error('email')
                            <p class="text-[#f53003] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">
                            Senha
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-white text-[#1b1b18] placeholder-[#706f6c] focus:outline-none focus:border-[#1b1b18] transition-colors"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="text-[#f53003] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-[#e3e3e0] text-[#1b1b18] focus:ring-[#1b1b18]"
                            >
                            <span class="ml-2 text-sm">Lembrar de mim</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-[#f53003] hover:underline">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-[#1b1b18] text-white py-2 px-4 rounded-sm font-medium hover:bg-black transition-colors"
                    >
                        Entrar
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-[#e3e3e0]"></div>
                    <span class="px-3 text-sm text-[#706f6c]">ou</span>
                    <div class="flex-1 border-t border-[#e3e3e0]"></div>
                </div>

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="text-center">
                        <p class="text-sm text-[#706f6c]">
                            Não tem uma conta? 
                            <a href="{{ route('register') }}" class="text-[#f53003] hover:underline font-medium">
                                Criar conta
                            </a>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-sm text-[#706f6c] hover:text-[#1b1b18] transition-colors">
                    ← Voltar para o início
                </a>
            </div>
        </div>
    </body>
</html>