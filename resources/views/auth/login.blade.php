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
                <div id="google-login-btn" style="display: block !important; opacity: 1 !important; visibility: visible !important;">
                    <a href="javascript:void(0);" onclick="loginWithGoogle()" class="w-full flex items-center justify-center gap-2 mb-4 px-4 py-2 rounded bg-white border border-[#e3e3e0] text-[#1b1b18] font-medium shadow hover:bg-gray-50 transition" style="display: flex !important; opacity: 1 !important; visibility: visible !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5"><g><path fill="#4285F4" d="M24 9.5c3.54 0 6.7 1.22 9.19 3.23l6.87-6.87C36.68 2.39 30.77 0 24 0 14.82 0 6.71 5.48 2.69 13.44l8.06 6.26C12.5 13.13 17.77 9.5 24 9.5z"/><path fill="#34A853" d="M46.1 24.55c0-1.64-.15-3.22-.43-4.74H24v9.01h12.42c-.54 2.9-2.18 5.36-4.65 7.03l7.19 5.6C43.93 37.13 46.1 31.36 46.1 24.55z"/><path fill="#FBBC05" d="M10.75 28.7c-1.01-2.99-1.01-6.41 0-9.4l-8.06-6.26C-1.13 18.52-1.13 29.48 2.69 36.56l8.06-6.26z"/><path fill="#EA4335" d="M24 44c6.77 0 12.68-2.39 17.06-6.5l-7.19-5.6c-2.01 1.35-4.6 2.1-7.87 2.1-6.23 0-11.5-3.63-13.25-8.8l-8.06 6.26C6.71 42.52 14.82 48 24 48z"/></g></svg>
                        Entrar com Google
                    </a>
                    <noscript>
                        <div style="color: red; font-weight: bold;">Ative o JavaScript para ver o botão de login com Google.</div>
                    </noscript>
                </div>
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
                    <div class="flex items-center justify-between mt-2">
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
                        class="w-full bg-[#1b1b18] text-white py-2 px-4 rounded-sm font-medium hover:bg-black transition-colors mt-4"
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
                    <div class="text-center mt-6">
                        <p class="text-sm text-[#706f6c] mb-2">Ainda não tem uma conta?</p>
                        <a href="{{ route('register') }}" class="inline-block w-full px-4 py-2 rounded bg-[#FF385C] text-white font-semibold text-sm hover:bg-[#e11d48] transition">Criar nova conta</a>
                    </div>
                @endif
            </div>
        </div>
        <script>
        function loginWithGoogle() {
            // Se estiver no app Android (WebView), chama o método nativo
            if (window.AndroidApp && typeof window.AndroidApp.loginWithGoogle === 'function') {
                window.AndroidApp.loginWithGoogle();
            } else {
                // Fora do app, faz o login Google normal do site
                window.location.href = "{{ route('google.login') }}";
            }
        }
        // Função para receber o idToken do app Android (opcional)
        window.onGoogleLogin = function(idToken) {
            // Exemplo: autenticar no backend
            fetch('/api/auth/google-mobile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idToken })
            })
            .then(response => response.json())
            .then(data => {
                // Redireciona ou recarrega a página após autenticar
                window.location.reload();
            });
        }
        </script>
    </body>
</html>