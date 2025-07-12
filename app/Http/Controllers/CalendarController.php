<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $imoveis = Imovel::where('user_id', auth()->id())->get();
        return view('calendar.index', compact('imoveis'));
    }

    public function show(Imovel $imovel)
    {
        // Verificar se o usuário tem acesso ao imóvel
        if ($imovel->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        // Sincronização automática se passou mais de 1 hora da última sincronização
        if ($imovel->ical_url && (!$imovel->last_ical_sync || $imovel->last_ical_sync->diffInHours(now()->setTimezone('America/Sao_Paulo')) >= 1)) {
            try {
                $this->autoSyncCalendar($imovel);
            } catch (\Exception $e) {
                // Silenciar erros de sincronização automática
            }
        }

        $events = $this->getCalendarEvents($imovel);
        return view('calendar.show', compact('imovel', 'events'));
    }

    public function updateIcalUrl(Request $request, Imovel $imovel)
    {
        // Verificar se o usuário tem acesso ao imóvel
        if ($imovel->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        $request->validate([
            'ical_url' => 'required|url'
        ]);

        $imovel->update([
            'ical_url' => $request->ical_url,
            'last_ical_sync' => null, // Reset sync para forçar nova sincronização
            'calendar_events' => null
        ]);

        return redirect()->route('calendar.show', $imovel)
            ->with('success', 'URL do iCal atualizada com sucesso!');
    }

    public function syncCalendar(Imovel $imovel)
    {
        // Verificar se o usuário tem acesso ao imóvel
        if ($imovel->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        if (!$imovel->ical_url) {
            return redirect()->route('calendar.show', $imovel)
                ->with('error', 'URL do iCal não configurada');
        }

        try {
            $events = $this->fetchAndParseIcal($imovel->ical_url);
            
            $imovel->update([
                'calendar_events' => $events,
                'last_ical_sync' => now()->setTimezone('America/Sao_Paulo')
            ]);
            
            // Forçar limpeza do cache para garantir que as traduções sejam aplicadas
            Cache::forget('calendar_events_' . $imovel->id);

            return redirect()->route('calendar.show', $imovel)
                ->with('success', 'Calendário sincronizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('calendar.show', $imovel)
                ->with('error', 'Erro ao sincronizar calendário: ' . $e->getMessage());
        }
    }

    private function getCalendarEvents(Imovel $imovel)
    {
        if (!$imovel->calendar_events) {
            return [];
        }

        $events = $imovel->calendar_events;
        
        // Converter strings de data de volta para objetos Carbon (já estão no fuso correto)
        foreach ($events as &$event) {
            if (isset($event['start']) && is_string($event['start'])) {
                try {
                    $event['start'] = Carbon::parse($event['start'], 'America/Sao_Paulo');
                } catch (\Exception $e) {
                    // Se falhar, tenta criar manualmente
                    $date = new \DateTime($event['start'], new \DateTimeZone('America/Sao_Paulo'));
                    $event['start'] = Carbon::instance($date);
                }
            }
            if (isset($event['end']) && is_string($event['end'])) {
                try {
                    $event['end'] = Carbon::parse($event['end'], 'America/Sao_Paulo');
                } catch (\Exception $e) {
                    // Se falhar, tenta criar manualmente
                    $date = new \DateTime($event['end'], new \DateTimeZone('America/Sao_Paulo'));
                    $event['end'] = Carbon::instance($date);
                }
            }
        }

        return $events;
    }

    private function fetchAndParseIcal($url)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
        ])->get($url);
        
        if (!$response->successful()) {
            throw new \Exception('Não foi possível acessar o arquivo iCal');
        }

        $icalContent = $response->body();
        
        return $this->parseIcalContent($icalContent);
    }

    private function parseIcalContent($content)
    {
        $events = [];
        $lines = explode("\n", $content);
        $currentEvent = null;
        $inEvent = false;

        foreach ($lines as $line) {
            $line = trim($line);
            
            if ($line === 'BEGIN:VEVENT') {
                $currentEvent = [];
                $inEvent = true;
                continue;
            }

            if ($line === 'END:VEVENT') {
                if ($currentEvent && isset($currentEvent['summary'])) {
                    $events[] = $currentEvent;
                }
                $currentEvent = null;
                $inEvent = false;
                continue;
            }

            if ($inEvent && $currentEvent !== null) {
                if (strpos($line, 'SUMMARY:') === 0) {
                    $summary = substr($line, 8);
                    // Traduzir summaries comuns
                    if ($summary === 'Reserved') {
                        $currentEvent['summary'] = 'Reservado';
                    } elseif ($summary === 'Airbnb (Not available)') {
                        $currentEvent['summary'] = 'Indisponível';
                    } else {
                        $currentEvent['summary'] = $summary;
                    }
                } elseif (strpos($line, 'DTSTART') === 0) {
                    // Pode ser DTSTART ou DTSTART;VALUE=DATE
                    $dateStr = preg_replace('/^DTSTART[^:]*:/', '', $line);
                    try {
                        $currentEvent['start'] = $this->parseIcalDate($dateStr);
                    } catch (\Exception $e) {
                        // Se não conseguir parsear a data, ignora
                    }
                } elseif (strpos($line, 'DTEND') === 0) {
                    // Pode ser DTEND ou DTEND;VALUE=DATE
                    $dateStr = preg_replace('/^DTEND[^:]*:/', '', $line);
                    try {
                        $currentEvent['end'] = $this->parseIcalDate($dateStr);
                    } catch (\Exception $e) {
                        // Se não conseguir parsear a data, ignora
                    }
                } elseif (strpos($line, 'DESCRIPTION:') === 0) {
                    $description = substr($line, 12);
                    // Traduzir descrições comuns
                    if (strpos($description, 'Reservation URL:') === 0) {
                        $currentEvent['description'] = '🔗 Link da reserva: ' . str_replace('Reservation URL: ', '', $description);
                    } else {
                        $currentEvent['description'] = $description;
                    }
                }
            }
        }

        return $events;
    }

    private function parseIcalDate($dateStr)
    {
        // Remove timezone se presente
        $dateStr = preg_replace('/[A-Z]{3}$/', '', $dateStr);
        
        // Formato: 20241201T120000Z ou 20241201
        if (strlen($dateStr) === 15) {
            // Com hora - converter de UTC para America/Sao_Paulo
            $year = substr($dateStr, 0, 4);
            $month = substr($dateStr, 4, 2);
            $day = substr($dateStr, 6, 2);
            $hour = substr($dateStr, 9, 2);
            $minute = substr($dateStr, 11, 2);
            $second = substr($dateStr, 13, 2);
            
            // Criar em UTC e converter para America/Sao_Paulo
            $utcDate = Carbon::create($year, $month, $day, $hour, $minute, $second, 'UTC');
            return $utcDate->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s');
        } elseif (strlen($dateStr) === 8) {
            // Apenas data (YYYYMMDD) - formato do Airbnb
            $year = substr($dateStr, 0, 4);
            $month = substr($dateStr, 4, 2);
            $day = substr($dateStr, 6, 2);
            
            // Criar em America/Sao_Paulo
            $date = Carbon::create($year, $month, $day, 0, 0, 0, 'America/Sao_Paulo');
            return $date->format('Y-m-d H:i:s');
        } else {
            // Tenta outros formatos
            throw new \Exception("Formato de data não reconhecido: $dateStr");
        }
    }

    private function autoSyncCalendar(Imovel $imovel)
    {
        if (!$imovel->ical_url) {
            return;
        }

        try {
            $events = $this->fetchAndParseIcal($imovel->ical_url);
            
            $imovel->update([
                'calendar_events' => $events,
                'last_ical_sync' => now()->setTimezone('America/Sao_Paulo')
            ]);
        } catch (\Exception $e) {
            // Silenciar erros de sincronização automática
        }
    }
}
