@extends('layout')
@section('content')
<div class="card p-4">
    <h2 class="mb-4 fw-bold"><i class="fa fa-plus"></i> Nova Despesa para {{ $locacao->nome }}</h2>
    <form action="{{ route('despesas.store', $locacao->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" required>
        </div>
        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Salvar</button>
            <a href="{{ route('locacoes.show', $locacao->id) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Cancelar</a>
        </div>
    </form>
</div>
@endsection 