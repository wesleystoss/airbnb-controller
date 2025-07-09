@extends('layout')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 border border-gray-100 mb-4">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-[#FF385C] rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#222]">Editar Perfil</h1>
                <p class="text-sm text-gray-500">Atualize suas informações pessoais</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Erro de validação</span>
                </div>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Editar Informações Pessoais -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#222] mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informações Pessoais
                </h3>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[#FF385C] focus:border-transparent">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[#FF385C] focus:border-transparent">
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-[#FF385C] text-white rounded-lg font-medium hover:bg-[#e11d48] transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Atualizar Informações
                        </button>
                    </div>
                </form>
            </div>

            <!-- Alterar Senha -->
            <div class="bg-blue-50 rounded-lg p-6 mt-8 mb-8">
                <h3 class="text-lg font-semibold text-[#222] mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Alterar Senha
                </h3>
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                        <input type="password" id="password" name="password" 
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" required>
                        <p class="text-xs text-gray-500 mt-1">Mínimo de 8 caracteres</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" required>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" style="background-color: #2563eb; color: white; border: 1px solid #1d4ed8; border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; transition: background-color 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 1rem; height: 1rem; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span style="color: white; font-weight: 500;">Alterar Senha</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <a href="{{ route('profile.show') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Voltar ao Perfil
                </a>
                <a href="{{ route('locacoes.index') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-[#FF385C] text-white rounded-lg font-medium hover:bg-[#e11d48] transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Ir para Locações
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 