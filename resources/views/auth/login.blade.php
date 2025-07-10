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
                        <h1 class="text-2xl font-bold text-gradient mb-2">Bem-vindo de volta</h1>
                        <p class="text-gray-500 text-sm">Entre na sua conta para continuar</p>
                    </div>

                    <!-- Google Login Button -->
                    <div class="mb-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.1s;">
                        <a href="#" onclick="handleGoogleLogin(event)" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl bg-white/80 backdrop-blur-sm border border-gray-200/50 text-gray-700 font-medium shadow-md hover:shadow-lg transition-all duration-300 hover-lift">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                <g>
                                    <path fill="#4285F4" d="M24 9.5c3.54 0 6.7 1.22 9.19 3.23l6.87-6.87C36.68 2.39 30.77 0 24 0 14.82 0 6.71 5.48 2.69 13.44l8.06 6.26C12.5 13.13 17.77 9.5 24 9.5z"/>
                                    <path fill="#34A853" d="M46.1 24.55c0-1.64-.15-3.22-.43-4.74H24v9.01h12.42c-.54 2.9-2.18 5.36-4.65 7.03l7.19 5.6C43.93 37.13 46.1 31.36 46.1 24.55z"/>
                                    <path fill="#FBBC05" d="M10.75 28.7c-1.01-2.99-1.01-6.21 0-9.2l-8.06-6.26C.23 17.09 0 20.49 0 24c0 3.51.23 6.91 2.69 10.76l8.06-6.26z"/>
                                    <path fill="#EA4335" d="M24 48c6.48 0 11.92-2.15 15.89-5.85l-7.19-5.6c-2.01 1.35-4.59 2.15-8.7 2.15-6.23 0-11.5-3.63-13.25-8.7l-8.06 6.26C6.71 42.52 14.82 48 24 48z"/>
                                    <path fill="none" d="M0 0h48v48H0z"/>
                                </g>
                            </svg>
                            Entrar com Google
                        </a>
                    </div>

                    <script>
                    function isAndroidApp() {
                        return typeof AndroidApp !== "undefined";
                    }
                    function handleGoogleLogin(e) {
                        if (isAndroidApp()) {
                            e.preventDefault();
                            AndroidApp.loginWithGoogle();
                        } else {
                            window.location.href = "{{ route('google.login') }}";
                        }
                    }
                    </script>

                    <!-- Divider -->
                    <div class="flex items-center mb-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.2s;">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="px-4 text-sm text-gray-400">ou</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.3s;">
                        @csrf
                        <!-- Email -->
                        <div>
                            <label for="email" class="label-modern">
                                Email
                            </label>
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
                            <label for="password" class="label-modern">
                                Senha
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="input-modern"
                                placeholder="••••••••"
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

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between mt-4" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.4s;">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    class="w-4 h-4 text-[#FF385C] bg-gray-100 border-gray-300 rounded focus:ring-[#FF385C] focus:ring-2"
                                >
                                <span class="ml-2 text-sm text-gray-600">Lembrar de mim</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-[#FF385C] hover:text-[#e11d48] transition-colors">
                                    Esqueceu?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="btn-primary w-full mt-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.5s;"
                        >
                            Entrar
                        </button>
                    </form>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center mt-8" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.6s;">
                            <p class="text-sm text-gray-500 mb-3">Ainda não tem uma conta?</p>
                            <a href="{{ route('register') }}" class="btn-secondary w-full">
                                Criar nova conta
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="text-center mt-6" x-show="show" x-transition.opacity.duration.700ms x-transition:enter-start="-translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" style="animation-delay: 0.7s;">
                    <p class="text-xs text-gray-400">
                        Uso pessoal • Não afiliado ao Airbnb
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>