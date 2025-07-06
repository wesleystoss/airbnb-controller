@extends('layout')
@section('content')
<div class="card p-4">
    <h2 class="mb-4 fw-bold"><i class="fa fa-pen"></i> Editar Locação</h2>
    <form action="{{ route('locacoes.update', $locacao->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ $locacao->nome }}" required>
        </div>
        <div class="mb-3">
            <label for="valor_total" class="form-label">Valor Total</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total" value="{{ $locacao->valor_total }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ $locacao->data_inicio }}" required>
            </div>
            <div class="col">
                <label for="data_fim" class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ $locacao->data_fim }}" required>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Salvar</button>
            <a href="{{ route('locacoes.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Cancelar</a>
        </div>
    </form>
</div>
@endsection 