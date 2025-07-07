@extends('layout')
@section('content')
<h1 class="text-lg font-bold text-[#222] mb-4 flex items-center gap-2"><svg xmlns='http://www.w3.org/2000/svg' class='w-5 h-5 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 3v4M8 3v4M4 11h16' /></svg> Locações</h1>
<div class="bg-white rounded-lg shadow-sm p-4 max-w-2xl mx-auto mb-6 border border-gray-100">
    <div class="flex items-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z" /></svg>
        <h4 class="font-bold text-base text-[#222]">Lucro Mensal</h4>
    </div>
    <canvas id="lucroChart" height="140" class="mb-4"></canvas>
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
    // Calcular totais por mês
    $coanfitriaoMensal = [];
    $despesasMensal = [];
    $locacoesMensal = [];
    foreach ($locacoes as $locacao) {
        $mes = \Carbon\Carbon::parse($locacao->data_inicio)->format('m/Y');
        $coanfitriao = round($locacao->valor_total * 0.3333, 2);
        $coanfitriaoMensal[$mes] = ($coanfitriaoMensal[$mes] ?? 0) + $coanfitriao;
        $despesas = $locacao->despesas->sum('valor');
        $despesasMensal[$mes] = ($despesasMensal[$mes] ?? 0) + $despesas;
        $locacoesMensal[$mes] = ($locacoesMensal[$mes] ?? 0) + $locacao->valor_total;
    }
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    const ctx = document.getElementById('lucroChart').getContext('2d');
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
            plugins: {
                legend: { display: true },
                title: { display: false },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    offset: -8,
                    color: '#222',
                    font: { weight: 'bold', size: 12 },
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
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
{{-- Seção de Locações --}}
<div class="mt-8">
    <div class="flex items-center gap-2 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4M4 11h16" /></svg>
        <h2 class="text-lg font-bold text-[#222]">Locações do mês</h2>
        <form method="GET" class="ml-auto flex items-center gap-2">
            <label for="periodo" class="text-xs text-gray-500">Período:</label>
            <select id="periodo" name="periodo" class="rounded border border-gray-200 px-2 py-1 text-xs focus:ring-2 focus:ring-[#FF385C] focus:outline-none w-56 min-w-full" style="min-width:200px; width:240px;">
                @foreach($mesesDisponiveis as $mesLabel => $mesValue)
                    <option value="{{ $mesValue }}" @if($periodo == $mesValue) selected @endif>{{ $mesLabel }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-1 rounded bg-[#FF385C] text-white text-xs font-medium hover:bg-[#e11d48] transition shadow-sm">Filtrar</button>
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @forelse($locacoes as $locacao)
        <div class="bg-white rounded-lg shadow-sm p-3 flex flex-col gap-1 border border-gray-100">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z' /></svg>
                <span class="font-semibold text-base text-[#222]">{{ $locacao->nome }}</span>
            </div>
            @php
                $coanfitriao = $locacao->valor_total * 0.3333;
                $totalDespesas = $locacao->despesas->sum('valor');
                $saldoFinal = $locacao->valor_total - $coanfitriao - $totalDespesas;
            @endphp
            <div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-1">
                <span class="inline-flex items-center gap-1">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z' /></svg>
                    {{ \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') }}
                </span>
                <span class="inline-flex items-center gap-1">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg>
                    Valor: <span class="font-semibold text-[#222]">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span>
                    <span class="mx-1">|</span>
                    Dias: <span class="font-semibold text-[#222]">{{ \Carbon\Carbon::parse($locacao->data_inicio)->diffInDays(\Carbon\Carbon::parse($locacao->data_fim)) + 1 }}</span>
                    <span class="mx-1">|</span>
                    Diária: <span class="font-semibold text-[#222]">R$ {{ number_format($locacao->valor_total / (\Carbon\Carbon::parse($locacao->data_inicio)->diffInDays(\Carbon\Carbon::parse($locacao->data_fim)) + 1), 2, ',', '.') }}</span>
                </span>
                <span class="inline-flex items-center gap-1 bg-yellow-50 border border-yellow-100 rounded px-2 py-0.5 text-yellow-700 font-semibold">
                    🤝 Co-anfitrião: R$ {{ number_format($coanfitriao, 2, ',', '.') }}
                </span>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="inline-flex items-center gap-1 text-gray-700">
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg>
                    Saldo final: <span class="font-bold {{ $saldoFinal < 0 ? 'text-red-500' : 'text-green-600' }}">R$ {{ number_format($saldoFinal, 2, ',', '.') }}</span>
                </span>
            </div>
            <div class="flex gap-2 mt-2">
                <a href="{{ route('locacoes.show', $locacao->id) }}" class="flex items-center gap-1 px-2 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-medium transition shadow-sm"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z' /></svg> Detalhes</a>
                <a href="{{ route('locacoes.edit', $locacao->id) }}" class="flex items-center gap-1 px-2 py-1 rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs font-medium transition shadow-sm"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z' /></svg> Editar</a>
                <form action="{{ route('locacoes.destroy', $locacao->id) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1 px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 text-xs font-medium transition shadow-sm"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' /></svg> Excluir</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center text-gray-500 py-8">Nenhuma locação cadastrada para este período.</div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $locacoes->links() }}
    </div>
</div>
@endsection 