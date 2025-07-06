@extends('layout')
@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 max-w-md mx-auto border border-gray-100">
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
            <button type="submit" class="flex items-center gap-1 px-4 py-2 rounded bg-[#FF385C] text-white text-xs font-medium hover:bg-[#e11d48] transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg> 
                Salvar
            </button>
            <button type="submit" name="criar_e_continuar" value="1" style="background-color: #059669; color: white; border: 1px solid #047857; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 500; display: flex; align-items: center; gap: 0.25rem; transition: background-color 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 1rem; height: 1rem; flex-shrink: 0; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                </svg> 
                <span style="color: white; font-weight: 500;">Criar e Continuar</span>
            </button>
            <a href="{{ route('locacoes.show', $locacao->id) }}" class="flex items-center gap-1 px-4 py-2 rounded bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg> 
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection 