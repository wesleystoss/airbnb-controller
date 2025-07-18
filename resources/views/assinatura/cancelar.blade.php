@extends('layout')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Cancelar assinatura</h2>
    @if(isset($mensagemEstorno))
        <div class="mb-4 p-3 rounded border @if($tipoEstorno==='integral') border-green-300 bg-green-50 text-green-800 @elseif($tipoEstorno==='parcial') border-yellow-300 bg-yellow-50 text-yellow-800 @else border-gray-200 bg-gray-50 text-gray-600 @endif">
            {{ $mensagemEstorno }}
        </div>
    @endif
    <div id="argumentario-cancelamento">
        <div class="mb-6 p-6 bg-gradient-to-br from-blue-50 via-pink-50 to-purple-50 border-2 border-pink-200 rounded-2xl shadow text-gray-900">
            <h3 class="font-extrabold text-2xl mb-4 flex items-center gap-2">
                <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
                Tem certeza que deseja cancelar?
            </h3>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <span class="bg-green-100 text-green-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span><strong>Gestão fácil:</strong> Controle total dos seus imóveis, locações e finanças em um só lugar.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    <span><strong>Relatórios automáticos:</strong> Receba relatórios prontos e agenda integrada ao Airbnb.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-yellow-100 text-yellow-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <span><strong>Suporte humano:</strong> Atendimento rápido, sem robôs, sempre que precisar.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-pink-100 text-pink-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    </span>
                    <span><strong>Cancelamento fácil:</strong> Você pode cancelar a qualquer momento, sem burocracia.</span>
                </div>
            </div>
            <div class="text-center mt-4">
                <span class="inline-block bg-white border border-pink-200 text-pink-700 px-4 py-2 rounded-full font-semibold shadow">Tem alguma dúvida, dificuldade ou sugestão? <br> <span class="underline">Fale com nosso suporte!</span></span>
            </div>
            <div class="text-center mt-6">
                <button id="mostrar-form-cancelamento" type="button" class="bg-gradient-to-r from-gray-200 to-pink-100 hover:from-pink-200 hover:to-gray-100 text-red-700 font-bold px-6 py-3 rounded-full transition shadow-lg">Quero cancelar mesmo assim</button>
            </div>
        </div>
    </div>
    <form id="form-cancelamento" method="POST" action="{{ route('assinatura.cancelar') }}" style="display:none;">
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
<script>
    document.getElementById('mostrar-form-cancelamento').onclick = function() {
        document.getElementById('argumentario-cancelamento').style.display = 'none';
        document.getElementById('form-cancelamento').style.display = 'block';
    };
</script>
@endsection 