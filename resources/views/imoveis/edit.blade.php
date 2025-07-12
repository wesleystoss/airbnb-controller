@extends('layout')
@section('content')
<div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <h2 class="text-lg font-bold text-[#222]">Editar Imóvel</h2>
        </div>
        
        <form action="{{ route('imoveis.update', $imovel->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="nome" class="block text-xs text-gray-600 mb-1">Nome do Imóvel <span class="text-red-500">*</span></label>
                <input type="text" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="nome" name="nome" value="{{ old('nome', $imovel->nome) }}" required>
                @error('nome')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="btn-action btn-action-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Atualizar Imóvel
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