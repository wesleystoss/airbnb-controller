@extends('layout')
@section('content')
<div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6 max-w-md mx-auto max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Nova Locação</h2>
    </div>
    <form action="{{ route('locacoes.store') }}" method="POST" class="flex flex-col gap-3">
        @csrf
        <div>
            <label for="imovel_id" class="block text-xs text-gray-600 mb-1">Imóvel <span class="text-red-500">*</span></label>
            <select id="imovel_id" name="imovel_id" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" required>
                <option value="">Selecione um imóvel</option>
                @foreach($imoveis as $imovel)
                    <option value="{{ $imovel->id }}">{{ $imovel->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="nome" class="block text-xs text-gray-600 mb-1">Tipo <span class="text-red-500">*</span></label>
            <select class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="nome" name="nome" required>
                <option value="Locação">Locação</option>
                <option value="Extensão">Extensão</option>
            </select>
        </div>
        <div>
            <label for="valor_total" class="block text-xs text-gray-600 mb-1">Valor Total <span class="text-red-500">*</span></label>
            <div class="flex items-center gap-1">
                <span class="text-gray-500 text-xs">R$</span>
                <input type="number" step="0.01" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="valor_total" name="valor_total" required>
            </div>
        </div>
        <div class="flex gap-2">
            <div class="w-1/2">
                <label for="data_inicio" class="block text-xs text-gray-600 mb-1">Data Início</label>
                <input type="date" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="data_inicio" name="data_inicio">
            </div>
            <div class="w-1/2">
                <label for="data_fim" class="block text-xs text-gray-600 mb-1">Data Fim</label>
                <input type="date" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="data_fim" name="data_fim">
            </div>
            <div class="w-1/2">
                <label for="data_pagamento" class="block text-xs text-gray-600 mb-1">Data de Pagamento <span class="text-red-500">*</span></label>
                <input type="date" class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none" id="data_pagamento" name="data_pagamento" required>
            </div>
        </div>
        <div class="flex gap-2 mt-2">
            <button type="submit" class="btn-action btn-action-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg> 
                Salvar
            </button>
            <a href="{{ route('locacoes.index') }}" class="btn-action btn-action-details">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg> 
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection 