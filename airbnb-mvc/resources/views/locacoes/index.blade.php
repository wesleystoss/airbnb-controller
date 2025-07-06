@extends('layout')
@section('content')
<h1 class="text-lg font-bold text-[#222] mb-4 flex items-center gap-2"><svg xmlns='http://www.w3.org/2000/svg' class='w-5 h-5 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 3v4M8 3v4M4 11h16' /></svg> Locações</h1>
<div class="card p-4 mb-4">
    <h4 class="fw-bold mb-3"><i class="fa fa-chart-line"></i> Lucro Mensal</h4>
    <canvas id="lucroChart" height="80"></canvas>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="alert alert-primary">
                <strong>Resumo do mês atual ({{ now()->format('m/Y') }}):</strong><br>
                R$ {{ number_format($resumoMensal[now()->format('m/Y')] ?? 0, 2, ',', '.') }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-success">
                <strong>Resumo do ano ({{ now()->format('Y') }}):</strong><br>
                R$ {{ number_format($resumoAnual[now()->format('Y')] ?? 0, 2, ',', '.') }}
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('lucroChart').getContext('2d');
    const lucroChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($resumoMensal)) !!},
            datasets: [{
                label: 'Lucro Mensal (R$)',
                data: {!! json_encode(array_values($resumoMensal)) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                borderRadius: 8,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
</script>
<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    @forelse($locacoes as $locacao)
    <div class="bg-white rounded-lg shadow-sm p-3 flex flex-col gap-1 border border-gray-100">
        <div class="flex items-center gap-2 mb-1">
            <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 011.42.59l1.3 1.3a2 2 0 001.42.59H19a2 2 0 012 2v10a2 2 0 01-2 2z' /></svg>
            <span class="font-semibold text-base text-[#222]">{{ $locacao->nome }}</span>
        </div>
        <div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-1">
            <span class="inline-flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg> {{ \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') }}</span>
            <span class="inline-flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg> Valor: <span class="font-semibold text-[#222]">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span></span>
        </div>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="inline-flex items-center gap-1 text-gray-700"><svg xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' /></svg> Saldo: <span class="font-bold {{ ($locacao->valor_total - $locacao->despesas->sum('valor')) < 0 ? 'text-red-500' : 'text-green-600' }}">R$ {{ number_format($locacao->valor_total - $locacao->despesas->sum('valor'), 2, ',', '.') }}</span></span>
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
    <div class="col-span-2 text-center text-gray-500 py-8">Nenhuma locação cadastrada ainda.</div>
    @endforelse
</div>
@endsection 