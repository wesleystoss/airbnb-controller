@extends('layout')
@section('content')
<div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h2 class="text-lg font-bold text-[#222]">Adicionar Imóvel</h2>
        </div>
        
        <form action="{{ route('imoveis.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nome" class="block text-xs text-gray-600 mb-1">Nome do Imóvel <span class="text-red-500">*</span></label>
                <input type="text" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="nome" name="nome" value="{{ old('nome') }}" required>
                @error('nome')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email_compartilhado" class="block text-xs text-gray-600 mb-1">E-mails para Compartilhamento (opcional)</label>
                <input type="text" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="email_compartilhado" name="email_compartilhado" value="{{ old('email_compartilhado') }}" placeholder="email1@exemplo.com, email2@exemplo.com">
                <p class="text-xs text-gray-500 mt-1">Separe múltiplos e-mails por vírgula</p>
                @error('email_compartilhado')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="btn-action btn-action-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Criar Imóvel
                </button>
                <a href="{{ route('imoveis.index') }}" class="btn-action btn-action-details">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 