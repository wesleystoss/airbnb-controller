@extends('layout')
@section('content')
<h1 class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto text-lg font-bold text-[#222] mb-4 flex items-center gap-2"><svg xmlns='http://www.w3.org/2000/svg' class='w-5 h-5 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 3v4M8 3v4M4 11h16' /></svg> Locações</h1>
<div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z" /></svg>
            <h4 class="font-bold text-base text-[#222]">Lucro Mensal</h4>
        </div>
        <div class="overflow-x-auto w-full">
            <canvas id="lucroChart" height="300" class="min-w-[550px] min-h-[440px]"></canvas>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-lg p-4 shadow-sm">
                <span class="text-2xl">📅</span>
                <div>
                    <div class="text-xs text-gray-500 font-semibold">Resumo do mês atual ({{ now()->format('m/Y') }}):</div>
                    <div class="text-xl font-bold text-blue-900">R$ {{ number_format($resumoMensal[now()->format('m/Y')] ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-lg p-4 shadow-sm">
                <span class="text-2xl">📈</span>
                <div>
                    <div class="text-xs text-gray-500 font-semibold">Resumo do ano ({{ now()->format('Y') }}):</div>
                    <div class="text-xl font-bold text-green-900">R$ {{ number_format($resumoAnual[now()->format('Y')] ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    @php
        // Arrays já vêm prontos do controller: $locacoesMensal, $coanfitriaoMensal, $despesasMensal
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        // Não definir min-width ou width no canvas
        const ctx = document.getElementById('lucroChart').getContext('2d');
        const isMobile = window.innerWidth < 640;
        const lucroChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($resumoMensal)) !!},
                datasets: [
                    {
                        label: 'Locações (R$)',
                        data: {!! json_encode(array_values($locacoesMensal)) !!},
                        backgroundColor: '#a78bfa', // roxo Tailwind 400
                        borderColor: '#7c3aed', // roxo Tailwind 600
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 40
                    },
                    {
                        label: 'Co-anfitrião (R$)',
                        data: {!! json_encode(array_values($coanfitriaoMensal)) !!},
                        backgroundColor: '#ff385c',
                        borderColor: '#ff385c',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 40
                    },
                    {
                        label: 'Despesas (R$)',
                        data: {!! json_encode(array_values($despesasMensal)) !!},
                        backgroundColor: '#3b82f6',
                        borderColor: '#1d4ed8',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 40
                    },
                    {
                        label: 'Saldo Final (R$)',
                        data: {!! json_encode(array_values($resumoMensal)) !!},
                        backgroundColor: '#22c55e',
                        borderColor: '#16a34a',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 40
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { font: { size: isMobile ? 12 : 14 } } },
                    title: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        offset: 12,
                        color: '#222',
                        font: { weight: 'bold', size: isMobile ? 10 : 12 },
                        formatter: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 32
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...{!! json_encode(array_values($locacoesMensal)) !!}, ...{!! json_encode(array_values($resumoMensal)) !!}, ...{!! json_encode(array_values($coanfitriaoMensal)) !!}, ...{!! json_encode(array_values($despesasMensal)) !!}) * 1.25,
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            display: true,
                            font: { size: isMobile ? 10 : 12 },
                            maxRotation: isMobile ? 45 : 0,
                            minRotation: isMobile ? 45 : 0
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
    {{-- Seção de Locações --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4M4 11h16" /></svg>
            <h2 class="text-lg font-bold text-[#222]">Locações do mês</h2>
            <form method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2 ml-auto w-full sm:w-auto">
                <label for="periodo" class="text-xs text-gray-500">Período:</label>
                <select id="periodo" name="periodo" class="rounded border border-gray-200 px-2 py-1 text-xs focus:ring-2 focus:ring-[#FF385C] focus:outline-none w-full sm:w-56 min-h-[40px]" style="min-width:0;">
                    @foreach($mesesDisponiveis as $mesLabel => $mesValue)
                        <option value="{{ $mesValue }}" @if($periodo == $mesValue) selected @endif>{{ $mesLabel }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-action btn-action-info min-h-[40px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                    </svg> 
                    Filtrar
                </button>
            </form>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($locacoes as $locacao)
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-4 mb-4 flex flex-col gap-1 text-base min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z' /></svg>
                <span class="font-semibold text-lg text-[#222]">{{ $locacao->nome }}</span>
            </div>
            <div class="text-xs text-gray-500 mb-1">
                <span class="font-semibold">Imóvel:</span> {{ $locacao->imovel?->nome ?? '-' }}
            </div>
            @php
                $coanfitriao = $locacao->valor_total * 0.3333;
                $totalDespesas = $locacao->despesas->sum('valor');
                $saldoFinal = $locacao->valor_total - $coanfitriao - $totalDespesas;
            @endphp
            <div class="flex flex-wrap gap-2 text-sm text-gray-500 mb-1">
                <span class="inline-flex items-center gap-1">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z' /></svg>
                    {{ $locacao->data_inicio ? \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') : '-' }} a {{ $locacao->data_fim ? \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') : '-' }}
                </span>
                <span class="inline-flex items-center gap-1">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg>
                    Valor: <span class="font-semibold text-[#222]">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span>
                    <span class="mx-1">|</span>
                    Dias: <span class="font-semibold text-[#222]">{{ $locacao->data_inicio && $locacao->data_fim ? \Carbon\Carbon::parse($locacao->data_inicio)->diffInDays(\Carbon\Carbon::parse($locacao->data_fim)) + 1 : '-' }}</span>
                    <span class="mx-1">|</span>
                    Diária: <span class="font-semibold text-[#222]">R$ {{ $locacao->data_inicio && $locacao->data_fim ? number_format($locacao->valor_total / (\Carbon\Carbon::parse($locacao->data_inicio)->diffInDays(\Carbon\Carbon::parse($locacao->data_fim)) + 1), 2, ',', '.') : '-' }}</span>
                </span>
                <span class="inline-flex items-center gap-1">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg>
                    Pagamento: <span class="font-semibold text-[#222]">{{ $locacao->data_pagamento ? \Carbon\Carbon::parse($locacao->data_pagamento)->format('d/m/Y') : '-' }}</span>
                </span>
                <div class="flex flex-col sm:flex-row gap-2 w-full">
                    <span class="inline-flex items-center gap-1 bg-yellow-50 border border-yellow-100 rounded px-2 py-0.5 text-yellow-700 font-semibold">
                        🤝 Co-anfitrião: R$ {{ number_format($coanfitriao, 2, ',', '.') }}
                    </span>
                    <span class="inline-flex items-center gap-1 bg-green-50 border border-green-100 rounded px-2 py-0.5 font-semibold {{ $saldoFinal < 0 ? 'bg-red-50 border-red-100 text-red-700' : 'text-green-700' }}">
                        💰 Saldo final: R$ {{ number_format($saldoFinal, 2, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="{{ route('locacoes.show', $locacao->id) }}" class="btn-action btn-action-details">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z' />
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' />
                    </svg> 
                    Ver
                </a>
                <a href="{{ route('locacoes.edit', $locacao->id) }}" class="btn-action btn-action-edit">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' />
                    </svg> 
                    Editar
                </a>
                <form action="{{ route('locacoes.destroy', $locacao->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-action-delete" onclick="return confirm('Tem certeza que deseja excluir esta locação?')">
                        <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />
                        </svg> 
                        Excluir
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center text-gray-500 py-8">Nenhuma locação cadastrada para este período.</div>
        @endforelse
    </div>
    <div class="mt-6">
        {{-- {{ $locacoes->links() }} --}}
    </div>
</div>
@endsection 