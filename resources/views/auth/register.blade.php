<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar - Airbnb</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm" x-data="{ show: false }" x-init="setTimeout(() => show = true, 10)">
        <div x-show="show" x-transition.opacity.duration.700ms>
            <!-- Card Principal -->
            <div class="card card-hover p-8">
                <!-- Header -->
                <div class="text-center mb-8" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-[#FF385C] to-[#e11d48] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-8 h-8 text-white">
                            <path fill="currentColor" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gradient mb-2">Criar nova conta</h1>
                    <p class="text-gray-500 text-sm">Preencha os dados abaixo para se cadastrar</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.1s;">
                    @csrf
                    <!-- Name -->
                    <div>
                        <label for="name" class="label-modern">Nome</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            class="input-modern"
                            placeholder="Seu nome completo"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="email" class="label-modern">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            class="input-modern"
                            placeholder="seu@email.com"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div>
                        <label for="password" class="label-modern">Senha</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="input-modern"
                            placeholder="Mínimo 6 caracteres"
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="label-modern">Confirmar Senha</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            class="input-modern"
                            placeholder="Repita a senha"
                        >
                    </div>
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full mt-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.2s;"
                    >
                        Criar conta
                    </button>
                </form>

                <!-- Login Link -->
                <div class="text-center mt-8" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.3s;">
                    <p class="text-sm text-gray-500 mb-3">Já tem uma conta?</p>
                    <a href="{{ route('login') }}" class="btn-secondary w-full">
                        Entrar
                    </a>
                </div>
            </div>
            <!-- Footer -->
            <div class="text-center mt-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.4s;">
                <p class="text-xs text-gray-400">
                    Uso pessoal • Não afiliado ao Airbnb
                </p>
            </div>
        </div>
    </div>
</body>
</html> 