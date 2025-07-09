@extends('layout')
@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 max-w-md mx-auto border border-gray-100 max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Editar Despesa</h2>
    </div>
    <form action="{{ route('despesas.update', $despesa->id) }}" method="POST" class="flex flex-col gap-3">
        @csrf
        @method('PUT')
        <div>
            <label for="descricao" class="block text-xs text-gray-600 mb-1">Descrição</label>
            <input type="text" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="descricao" name="descricao" value="{{ $despesa->descricao }}" required>
        </div>
        <div>
            <label for="valor" class="block text-xs text-gray-600 mb-1">Valor</label>
            <div class="flex items-center gap-1">
                <span class="text-gray-500 text-xs">R$</span>
                <input type="number" step="0.01" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="valor" name="valor" value="{{ $despesa->valor }}" required>
            </div>
        </div>

        <div class="flex gap-2 mt-2">
            <button type="submit" class="flex items-center gap-1 px-4 py-2 rounded bg-[#FF385C] text-white text-xs font-medium hover:bg-[#e11d48] transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" /></svg> Salvar</button>
            <a href="{{ route('locacoes.show', $despesa->locacao_id) }}" class="flex items-center gap-1 px-4 py-2 rounded bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200 transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Cancelar</a>
        </div>
    </form>
</div>
@endsection 