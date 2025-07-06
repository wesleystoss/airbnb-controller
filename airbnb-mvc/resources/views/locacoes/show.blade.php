@extends('layout')
@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 mb-4 border border-gray-100">
    <div class="flex items-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Detalhes da Locação</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm mb-2">
        <div>
            <span class="text-gray-500">Nome:</span> <span class="font-semibold">{{ $locacao->nome }}</span><br>
            <span class="text-gray-500">Período:</span> <span class="inline-flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg> {{ \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') }}</span>
        </div>
        <div>
            <span class="text-gray-500">Valor Total:</span> <span class="font-semibold">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span><br>
            <span class="text-gray-500">Co-anfitrião (33,33%):</span> <span class="font-semibold text-[#FF385C]">R$ {{ number_format($coanfitriao, 2, ',', '.') }}</span><br>
            <span class="text-gray-500">Despesas:</span> <span class="font-semibold">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span><br>
            <span class="text-gray-500">Saldo Final:</span> <span class="font-bold {{ $saldo < 0 ? 'text-red-500' : 'text-green-600' }}">R$ {{ number_format($saldo, 2, ',', '.') }}</span>
        </div>
    </div>
    <div class="bg-gray-50 border border-gray-200 rounded p-2 text-xs mb-3">
        <span class="font-semibold">Racional do cálculo:</span><br>
        Valor total: <b>R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</b> - Co-anfitrião: <b>R$ {{ number_format($coanfitriao, 2, ',', '.') }}</b> - Despesas: <b>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</b> = <b>Saldo final: R$ {{ number_format($saldo, 2, ',', '.') }}</b>
    </div>
    <div class="flex gap-2 mb-2">
        <a href="{{ route('despesas.create', $locacao->id) }}" class="flex items-center gap-1 px-3 py-1 rounded bg-[#FF385C] text-white text-xs font-medium hover:bg-[#e11d48] transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> Nova Despesa</a>
        <a href="{{ route('locacoes.edit', $locacao->id) }}" class="flex items-center gap-1 px-3 py-1 rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs font-medium transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" /></svg> Editar</a>
        <a href="{{ route('locacoes.index') }}" class="flex items-center gap-1 px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-medium transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" /></svg> Voltar</a>
    </div>
</div>
<div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
    <h4 class="font-bold text-sm text-[#222] mb-2 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" /></svg> Despesas</h4>
    @if($locacao->despesas->isEmpty())
        <div class="text-gray-500 text-xs py-4 text-center">Nenhuma despesa cadastrada para esta locação.</div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="py-2 px-2 text-left">Descrição</th>
                    <th class="py-2 px-2 text-left">Valor</th>
                    <th class="py-2 px-2 text-left">Data</th>
                    <th class="py-2 px-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locacao->despesas as $despesa)
                <tr class="border-b last:border-0">
                    <td class="py-2 px-2">{{ $despesa->descricao }}</td>
                    <td class="py-2 px-2">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                    <td class="py-2 px-2">{{ \Carbon\Carbon::parse($despesa->data)->format('d/m/Y') }}</td>
                    <td class="py-2 px-2 w-full">
                        <div class="flex justify-center gap-2 w-full">
                            <a href="{{ route('despesas.edit', $despesa->id) }}" class="flex items-center gap-1 px-2 py-1 rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs font-medium transition shadow-sm whitespace-nowrap"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" /></svg> Editar</a>
                            <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Tem certeza?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-1 px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 text-xs font-medium transition shadow-sm whitespace-nowrap"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Excluir</button>
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