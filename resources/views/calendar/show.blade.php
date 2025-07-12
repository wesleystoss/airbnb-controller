@extends('layout')
@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50/80 backdrop-blur-sm border border-green-200/50 rounded-2xl text-green-800 text-sm shadow-lg max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-50/80 backdrop-blur-sm border border-red-200/50 rounded-2xl text-red-800 text-sm shadow-lg max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif
<h1 class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto text-lg font-bold text-[#222] mb-4 flex items-center gap-2">
    <svg xmlns='http://www.w3.org/2000/svg' class='w-5 h-5 text-[#FF385C] flex-shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' />
    </svg> 
    Calendário - {{ $imovel->nome }}
</h1>

<div class="max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    {{-- Configuração do iCal --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h2 class="text-lg font-bold text-[#222]">Configuração do iCal</h2>
        </div>
        <form action="{{ route('calendar.update-ical', $imovel) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="ical_url" class="block text-xs text-gray-600 mb-1">URL do iCal do Airbnb</label>
                <input type="url" 
                       id="ical_url" 
                       name="ical_url" 
                       value="{{ $imovel->ical_url }}"
                       placeholder="https://www.airbnb.com/calendar/ical/..."
                       class="w-full rounded border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF385C] focus:outline-none"
                       required>
                <p class="text-xs text-gray-500 mt-1">
                    Cole aqui a URL do iCal do seu imóvel no Airbnb
                </p>
            </div>
            <button type="submit" class="btn-action btn-action-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Salvar URL
            </button>
        </form>
        @if($imovel->ical_url)
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-semibold text-blue-700">Como obter a URL do iCal:</span>
                </div>
                <ol class="text-xs text-blue-600 space-y-1 ml-6 list-decimal">
                    <li>Acesse o Airbnb e faça login</li>
                    <li>Vá em "Anfitrião" > "Meus anúncios"</li>
                    <li>Clique no seu anúncio</li>
                    <li>Na seção "Disponibilidade", procure por "Exportar calendário"</li>
                    <li>Copie a URL que aparece</li>
                </ol>
            </div>
        @endif
    </div>
    {{-- Sincronização --}}
    @if($imovel->ical_url)
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <div class="flex items-center justify-between mb-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <h2 class="text-lg font-bold text-[#222]">Sincronização</h2>
                </div>
                <div class="sincronizar-mobile-center w-full sm:w-auto">
                    <form action="{{ route('calendar.sync', $imovel) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-action btn-action-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Sincronizar Agora
                        </button>
                    </form>
                </div>
            </div>
            @if($imovel->last_ical_sync)
                <div class="flex items-center gap-2 text-sm text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Última sincronização: {{ $imovel->last_ical_sync->format('d/m/Y H:i') }}
                    @if($imovel->last_ical_sync->diffInMinutes(now()->setTimezone('America/Sao_Paulo')) < 5)
                        <span class="text-xs text-blue-600">(atualizado automaticamente)</span>
                    @endif
                </div>
            @else
                <div class="flex items-center gap-2 text-sm text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    Nenhuma sincronização realizada ainda
                </div>
            @endif
        </div>
    @endif
    {{-- Calendário Visual --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h2 class="calendar-visual-title text-lg font-bold text-[#222]">Calendário Visual</h2>
            </div>
            <!-- (Aqui ficam os botões de navegação, se houver) -->
        </div>
        <!-- Removida a legenda do calendário do Blade para ser inserida via JS -->
        <div id="calendar"></div>
    </div>
    {{-- Lista de Eventos (acessibilidade/fallback) --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FF385C] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h2 class="text-lg font-bold text-[#222]">Eventos do Calendário</h2>
        </div>
        @if(empty($events))
            <div class="text-center text-gray-500 py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-semibold mb-2">Nenhum evento encontrado</p>
                <p class="text-sm">
                    @if($imovel->ical_url)
                        Sincronize o calendário para ver os eventos do Airbnb
                    @else
                        Configure a URL do iCal primeiro
                    @endif
                </p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($events as $event)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        @if(isset($event['summary']))
                                            @if($event['summary'] === 'Reserved' || strpos($event['summary'], 'Reservado') !== false)
                                                <span class="text-2xl">🏠</span>
                                                <h3 class="font-semibold text-[#222]">Reservado</h3>
                                            @elseif($event['summary'] === 'Airbnb (Not available)' || strpos($event['summary'], 'Indisponível') !== false)
                                                <span class="text-2xl">🚫</span>
                                                <h3 class="font-semibold text-[#222]">Indisponível</h3>
                                            @else
                                                <span class="text-2xl">📅</span>
                                                <h3 class="font-semibold text-[#222]">{{ $event['summary'] }}</h3>
                                            @endif
                                        @else
                                            <span class="text-2xl">📅</span>
                                            <h3 class="font-semibold text-[#222]">Evento sem título</h3>
                                        @endif
                                    </div>
                                    @if(isset($event['has_locacao']) && $event['has_locacao'])
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Registrada
                                        </span>
                                    @elseif(isset($event['summary']) && (strpos($event['summary'], 'Reserved') !== false || strpos($event['summary'], 'Reservado') !== false))
                                        <a href="{{ route('locacoes.create', ['imovel_id' => $imovel->id, 'data_inicio' => $event['start']->format('Y-m-d'), 'data_fim' => $event['end']->format('Y-m-d'), 'nome' => 'Reserva Airbnb - ' . $event['start']->format('d/m/Y')]) }}" 
                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Registrar
                                        </a>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    @if(isset($event['start']))
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Data de início: {{ $event['start']->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if(isset($event['end']))
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Data de fim: {{ $event['end']->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if(isset($event['description']))
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium">Detalhes:</span> 
                                        @if(strpos($event['description'], 'Reservation URL:') === 0)
                                            🔗 Link da reserva: {{ str_replace('Reservation URL: ', '', $event['description']) }}
                                        @else
                                            {{ $event['description'] }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    {{-- Botões de ação --}}
    <div class="flex gap-2">
        <a href="{{ route('calendar.index') }}" class="btn-action btn-action-details">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </a>
    </div>
</div>
@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                // Debug: mostrar eventos no console
                var eventos = @json($events);
                console.log('Eventos disponíveis:', eventos);

                // Converter datas para string ISO e adicionar cores/estilos
                eventos = eventos.map(function(ev) {
                    var evento = {
                        ...ev,
                        start: ev.start && ev.start.toISOString ? ev.start.toISOString() : ev.start,
                        end: ev.end && ev.end.toISOString ? ev.end.toISOString() : ev.end,
                    };
                    // Definir cores baseado no tipo de evento
                    if (ev.summary && (ev.summary.includes('Reservado') || ev.summary.includes('Reserved'))) {
                        evento.backgroundColor = '#FF385C';
                        evento.borderColor = '#e11d48';
                        evento.textColor = '#ffffff';
                        evento.title = '🏠 Reservado';
                    } else if (ev.summary && (ev.summary.includes('Indisponível') || ev.summary.includes('Not available'))) {
                        evento.backgroundColor = '#6B7280';
                        evento.borderColor = '#4B5563';
                        evento.textColor = '#ffffff';
                        evento.title = '🚫 Indisponível';
                    } else {
                        evento.backgroundColor = '#3B82F6';
                        evento.borderColor = '#2563EB';
                        evento.textColor = '#ffffff';
                        evento.title = '📅 ' + (ev.summary || 'Evento');
                    }
                    return evento;
                });

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'pt-br',
                    height: 600,
                    timeZone: 'America/Sao_Paulo',
                    buttonText: {
                        today: 'Hoje',
                        month: 'Mês',
                        week: 'Semana',
                        list: 'Lista'
                    },
                    dayHeaderFormat: { weekday: 'short' },
                    titleFormat: { 
                        year: 'numeric', 
                        month: 'long' 
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: eventos,
                    eventClick: function(info) {
                        var descricao = info.event.extendedProps.description || 'Sem descrição';
                        var inicio = info.event.start.toLocaleDateString('pt-BR');
                        var fim = info.event.end.toLocaleDateString('pt-BR');
                        var mensagem = '📅 ' + info.event.title + '\n\n' +
                                     '📅 Data de início: ' + inicio + '\n' +
                                     '📅 Data de fim: ' + fim + '\n\n' +
                                     '📝 Detalhes: ' + descricao;
                        alert(mensagem);
                    },
                    datesSet: function() {
                        // Adiciona a legenda acima do grid do calendário
                        let legend = calendarEl.querySelector('.calendar-legend-dynamic');
                        if (!legend) {
                            legend = document.createElement('div');
                            legend.className = 'calendar-legend calendar-legend-dynamic w-full mb-2';
                            legend.innerHTML = `
                                <div style="display:inline-flex;align-items:center;gap:0.3rem;font-size:1rem;">
                                    <span class="text-lg">🏠</span>
                                    <span class="text-gray-600">Reservado</span>
                                </div>
                                <div style="display:inline-flex;align-items:center;gap:0.3rem;font-size:1rem;margin-left:1.2rem;">
                                    <span class="text-lg">🚫</span>
                                    <span class="text-gray-600">Indisponível</span>
                                </div>
                            `;
                        }
                        // Remove se já existir
                        var oldLegend = calendarEl.querySelector('.calendar-legend-dynamic');
                        if (oldLegend) oldLegend.remove();
                        // Insere logo após o header do calendário
                        const fcHeader = calendarEl.querySelector('.fc-header-toolbar');
                        if (fcHeader && fcHeader.nextSibling) {
                            fcHeader.parentNode.insertBefore(legend, fcHeader.nextSibling);
                        } else {
                            calendarEl.prepend(legend);
                        }
                    }
                });
                calendar.render();
            }
        });
    </script>
@endpush
@endsection 