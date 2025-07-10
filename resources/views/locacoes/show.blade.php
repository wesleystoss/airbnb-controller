@extends('layout')
@section('content')
<div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6 max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Detalhes da Locação</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm mb-2">
        <div>
            <span class="text-gray-500">Imóvel:</span> <span class="font-semibold">{{ $locacao->imovel?->nome ?? '-' }}</span><br>
            @if($locacao->imovel && $locacao->imovel->compartilhamentos->count() > 0)
                <span class="text-gray-500">Compartilhado com:</span> 
                <span class="font-semibold">
                    @foreach($locacao->imovel->compartilhamentos as $compartilhamento)
                        {{ $compartilhamento->usuarioCompartilhado->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span><br>
            @endif
            <span class="text-gray-500">Tipo:</span> <span class="font-semibold">{{ $locacao->nome }}</span><br>
            <span class="text-gray-500">Período:</span> <span class="inline-flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg> {{ \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') }}</span><br>
            <span class="text-gray-500">Data de Pagamento:</span> <span class="font-semibold">{{ $locacao->data_pagamento ? \Carbon\Carbon::parse($locacao->data_pagamento)->format('d/m/Y') : '-' }}</span>
        </div>
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded px-2 py-1">
                <span class="text-lg">💰</span>
                <span class="text-gray-500">Valor Total:</span> <span class="font-semibold text-blue-900">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-100 rounded px-2 py-1">
                <span class="text-lg">🤝</span>
                <span class="text-gray-500">Co-anfitrião (33,33%):</span> <span class="font-semibold text-yellow-700">R$ {{ number_format($coanfitriao, 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2 bg-red-50 border border-red-100 rounded px-2 py-1">
                <span class="text-lg">💸</span>
                <span class="text-gray-500">Despesas:</span> <span class="font-semibold text-red-600">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2 bg-green-50 border border-green-100 rounded px-2 py-1">
                <span class="text-lg">🧮</span>
                <span class="text-gray-500">Saldo Final:</span> <span class="font-bold {{ $saldo < 0 ? 'text-red-500' : 'text-green-600' }}">R$ {{ number_format($saldo, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 border border-gray-200 rounded p-3 text-xs mb-3 mt-2 flex items-center gap-2">
        <span class="text-lg">📝</span>
        <span><span class="font-semibold">Racional do cálculo:</span><br>
        Valor total: <b>R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</b> - Co-anfitrião: <b>R$ {{ number_format($coanfitriao, 2, ',', '.') }}</b> - Despesas: <b>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</b> = <b>Saldo final: R$ {{ number_format($saldo, 2, ',', '.') }}</b></span>
    </div>
    <div class="flex flex-wrap gap-2 mb-3">
        <a href="{{ route('despesas.create', $locacao->id) }}" class="btn-action btn-action-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg> 
            Nova Despesa
        </a>
        <a href="{{ route('locacoes.edit', $locacao->id) }}" class="btn-action btn-action-edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' />
            </svg> 
            Editar
        </a>
        <form action="{{ route('locacoes.destroy', $locacao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta locação?')" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-action-delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />
                </svg> 
                Excluir
            </button>
        </form>
        <a href="{{ route('locacoes.index') }}" class="btn-action btn-action-details">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg> 
            Voltar
        </a>
    </div>
</div>
<div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6 max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <h4 class="font-bold text-sm text-[#222] mb-2 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" /></svg> Despesas</h4>
    @if($locacao->despesas->isEmpty())
        <div class="text-gray-500 text-xs py-4 text-center">Nenhuma despesa cadastrada para esta locação.</div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full table-fixed w-full text-xs">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="py-2 px-2 w-1/3 text-center">Descrição</th>
                    <th class="py-2 px-2 w-1/3 text-center">Valor</th>
                    <th class="py-2 px-2 w-1/3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locacao->despesas as $index => $despesa)
                    <tr class="border-b last:border-0 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="py-2 px-2 w-1/3 truncate text-center">
                            {{ $despesa->descricao }}
                        </td>
                        <td class="py-2 px-2 w-1/3 text-center">
                            R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                        </td>
                        <td class="py-2 px-2 w-1/3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('despesas.edit', $despesa->id) }}" class="btn-action btn-action-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>
                                <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Tem certeza?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection 