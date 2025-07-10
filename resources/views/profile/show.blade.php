@extends('layout')
@section('content')
<div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-[#FF385C] rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#222]">Meu Perfil</h1>
                <p class="text-sm text-gray-500">Gerencie suas informações pessoais</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="space-y-6">
            <!-- Informações Pessoais -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-[#222] mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informações Pessoais
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nome</label>
                        <p class="text-[#222] font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">E-mail</label>
                        <p class="text-[#222] font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Membro desde</label>
                        <p class="text-[#222] font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Última atualização</label>
                        <p class="text-[#222] font-medium">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-[#222] mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Estatísticas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-blue-100">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🏠</span>
                            <div>
                                <p class="text-xs text-gray-500">Total de Locações</p>
                                <p class="text-lg font-bold text-blue-900">{{ $user->locacoes->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-blue-100">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">💰</span>
                            <div>
                                <p class="text-xs text-gray-500">Total de Despesas</p>
                                <p class="text-lg font-bold text-blue-900">{{ $user->locacoes->sum(function($locacao) { return $locacao->despesas->count(); }) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('profile.edit') }}" class="btn-action btn-action-edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" />
                    </svg>
                    Editar Perfil
                </a>
                <a href="{{ route('locacoes.index') }}" class="btn-action btn-action-details">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 