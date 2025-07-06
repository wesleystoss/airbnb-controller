@extends('layout')
@section('content')
<div class="card p-4">
    <h2 class="mb-4 fw-bold"><i class="fa fa-pen"></i> Editar Despesa</h2>
    <form action="{{ route('despesas.update', $despesa->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" value="{{ $despesa->descricao }}" required>
        </div>
        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="{{ $despesa->valor }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" value="{{ $despesa->data }}" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Salvar</button>
            <a href="{{ route('locacoes.show', $despesa->locacao_id) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Cancelar</a>
        </div>
    </form>
</div>
@endsection 