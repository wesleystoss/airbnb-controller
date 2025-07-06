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
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <!-- Header -->
            <header class="text-center mb-8">
                <h1 class="text-2xl font-semibold mb-2 dark:text-[#EDEDEC]">Entrar</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">Bem-vindo de volta</p>
            </header>

            <!-- Login Form -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2 dark:text-[#EDEDEC]">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] transition-colors"
                            placeholder="seu@email.com"
                        >
                        @error('email')
                            <p class="text-[#f53003] dark:text-[#FF4433] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2 dark:text-[#EDEDEC]">
                            Senha
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] transition-colors"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="text-[#f53003] dark:text-[#FF4433] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                            >
                            <span class="ml-2 text-sm dark:text-[#EDEDEC]">Lembrar de mim</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] py-2 px-4 rounded-sm font-medium hover:bg-black dark:hover:bg-white transition-colors"
                    >
                        Entrar
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>
                    <span class="px-3 text-sm text-[#706f6c] dark:text-[#A1A09A]">ou</span>
                    <div class="flex-1 border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>
                </div>

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="text-center">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Não tem uma conta? 
                            <a href="{{ route('register') }}" class="text-[#f53003] dark:text-[#FF4433] hover:underline font-medium">
                                Criar conta
                            </a>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                    ← Voltar para o início
                </a>
            </div>
        </div>
    </body>
</html>