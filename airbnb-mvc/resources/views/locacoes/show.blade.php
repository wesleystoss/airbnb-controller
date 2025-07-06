@extends('layout')
@section('content')
<div class="card p-4 mb-4">
    <h2 class="fw-bold mb-3"><i class="fa fa-eye"></i> Detalhes da Locação</h2>
    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Nome:</strong> {{ $locacao->nome }}<br>
            <strong>Período:</strong> <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($locacao->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($locacao->data_fim)->format('d/m/Y') }}</span>
        </div>
        <div class="col-md-6">
            <strong>Valor Total:</strong> <span class="fw-bold">R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</span><br>
            <strong>Co-anfitrião (33,33%):</strong> <span class="fw-bold text-primary">R$ {{ number_format($coanfitriao, 2, ',', '.') }}</span><br>
            <strong>Total de Despesas:</strong> <span class="fw-bold">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span><br>
            <strong>Saldo Final:</strong> <span class="fw-bold {{ $saldo < 0 ? 'text-danger' : 'text-success' }}">R$ {{ number_format($saldo, 2, ',', '.') }}</span>
        </div>
    </div>
    <div class="alert alert-light border mt-3">
        <strong>Racional do cálculo:</strong><br>
        <span class="d-block">Valor total da locação: <b>R$ {{ number_format($locacao->valor_total, 2, ',', '.') }}</b></span>
        <span class="d-block">- Co-anfitrião (33,33%): <b>R$ {{ number_format($coanfitriao, 2, ',', '.') }}</b></span>
        <span class="d-block">- Despesas: <b>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</b></span>
        <span class="d-block">= <b>Saldo final: R$ {{ number_format($saldo, 2, ',', '.') }}</b></span>
    </div>
    <div class="d-flex gap-2 mb-2">
        <a href="{{ route('despesas.create', $locacao->id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Adicionar Despesa</a>
        <a href="{{ route('locacoes.edit', $locacao->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i> Editar Locação</a>
        <a href="{{ route('locacoes.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Voltar</a>
    </div>
</div>
<div class="card p-4">
    <h4 class="fw-bold mb-3"><i class="fa fa-money-bill-wave"></i> Despesas</h4>
    @if($locacao->despesas->isEmpty())
        <div class="alert alert-info">Nenhuma despesa cadastrada para esta locação.</div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locacao->despesas as $despesa)
                <tr>
                    <td>{{ $despesa->descricao }}</td>
                    <td>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($despesa->data)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pen"></i></a>
                        <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection 