@extends('layout')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Cancelar assinatura</h2>
    @if(isset($mensagemEstorno))
        <div class="mb-4 p-3 rounded border @if($tipoEstorno==='integral') border-green-300 bg-green-50 text-green-800 @elseif($tipoEstorno==='parcial') border-yellow-300 bg-yellow-50 text-yellow-800 @else border-gray-200 bg-gray-50 text-gray-600 @endif">
            {{ $mensagemEstorno }}
        </div>
    @endif
    <form method="POST" action="{{ route('assinatura.cancelar') }}">
        @csrf
        <div class="mb-4">
            <label for="motivo" class="block text-gray-700 font-semibold mb-2">Explique o motivo do cancelamento <span class="text-red-500">*</span></label>
            <textarea name="motivo" id="motivo" rows="5" class="w-full border rounded p-2 @error('motivo') border-red-500 @enderror" required>{{ old('motivo') }}</textarea>
            @error('motivo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Enviar pedido de cancelamento</button>
        <a href="{{ url()->previous() }}" class="ml-4 text-gray-600 hover:underline">Voltar</a>
    </form>
</div>
@endsection 