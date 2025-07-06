<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Controle</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f7f7f7] min-h-screen">
    <header class="w-full bg-white shadow-sm fixed top-0 left-0 z-20">
        <div class="max-w-4xl mx-auto flex items-center justify-between px-4 py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-[#FF385C]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-6 h-6"><path fill="#FF385C" d="M16 2.7c-2.2 0-4.2 1.2-5.3 3.1L3.2 18.2c-1.1 1.9-1.1 4.2 0 6.1c1.1 1.9 3.1 3.1 5.3 3.1h15c2.2 0 4.2-1.2 5.3-3.1c1.1-1.9 1.1-4.2 0-6.1L21.3 5.8C20.2 3.9 18.2 2.7 16 2.7m0 2c1.5 0 2.9.8 3.6 2.1l7.5 12.4c.7 1.3.7 2.9 0 4.2c-.7 1.3-2.1 2.1-3.6 2.1h-15c-1.5 0-2.9-.8-3.6-2.1c-.7-1.3-.7-2.9 0-4.2l7.5-12.4C13.1 5.5 14.5 4.7 16 4.7m0 4.3c-1.1 0-2.1.6-2.7 1.6l-6.2 10.2c-.6 1-.6 2.2 0 3.2c.6 1 1.6 1.6 2.7 1.6h12.4c1.1 0 2.1-.6 2.7-1.6c.6-1 .6-2.2 0-3.2l-6.2-10.2c-.6-1-1.6-1.6-2.7-1.6m0 2c.5 0 1 .3 1.3.7l6.2 10.2c.3.5.3 1.1 0 1.6c-.3.5-.8.7-1.3.7H9.8c-.5 0-1-.3-1.3-.7c-.3-.5-.3-1.1 0-1.6l6.2-10.2c.3-.5.8-.7 1.3-.7"/></svg>
                Airbnb Controle
            </a>
            <a href="{{ route('locacoes.create') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FF385C] text-white text-sm font-semibold hover:bg-[#e11d48] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nova Locação
            </a>
        </div>
    </header>
    <main class="max-w-4xl mx-auto pt-20 px-2 sm:px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html> 