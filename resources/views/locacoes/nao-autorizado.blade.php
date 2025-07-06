@extends('layout')
@section('content')
<div class="max-w-md mx-auto mt-16 bg-white rounded-lg shadow p-6 text-center border border-red-200">
    <h2 class="text-2xl font-bold text-red-600 mb-2">Acesso negado</h2>
    <p class="text-gray-700">{{ $mensagem ?? 'Você não tem permissão para acessar esta locação.' }}</p>
    <a href="{{ route('locacoes.index') }}" class="inline-block mt-6 px-4 py-2 bg-[#FF385C] text-white rounded hover:bg-[#e11d48] transition">Voltar para minhas locações</a>
</div>
@endsection 