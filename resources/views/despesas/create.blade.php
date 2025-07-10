@extends('layout')
@section('content')
<div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6 max-w-md mx-auto max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Nova Despesa para {{ $locacao->nome }}</h2>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    <form action="{{ route('despesas.store', $locacao->id) }}" method="POST" class="flex flex-col gap-3">
        @csrf
        <div>
            <label for="descricao" class="block text-xs text-gray-600 mb-1">Descrição</label>
            <input type="text" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="descricao" name="descricao" required>
        </div>
        <div>
            <label for="valor" class="block text-xs text-gray-600 mb-1">Valor</label>
            <div class="flex items-center gap-1">
                <span class="text-gray-500 text-xs">R$</span>
                <input type="number" step="0.01" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="valor" name="valor" required>
            </div>
        </div>

        <div class="flex gap-2 mt-2">
            <button type="submit" class="btn-action btn-action-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg> 
                Salvar
            </button>
            <button type="submit" name="criar_e_continuar" value="1" class="btn-action btn-action-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                </svg> 
                Criar e Continuar
            </button>
            <a href="{{ route('locacoes.show', $locacao->id) }}" class="btn-action btn-action-details">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg> 
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection 