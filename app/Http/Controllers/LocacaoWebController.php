<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Imovel;
use App\Models\CompartilhamentoImovel;

class LocacaoWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home(Request $request)
    {
        $user = Auth::user();
        
        // Locações do usuário
        $locacoesUsuario = $user->locacoes()->with('despesas')->get();
        
        // Locações de imóveis compartilhados
        $imoveisCompartilhadosIds = CompartilhamentoImovel::where('user_compartilhado_id', $user->id)->pluck('imovel_id');
        $locacoesCompartilhadas = \App\Models\Locacao::with('despesas')
            ->whereIn('imovel_id', $imoveisCompartilhadosIds)
            ->get();
        
        // Unir e remover duplicatas
        $todasLocacoes = $locacoesUsuario->merge($locacoesCompartilhadas)->unique('id');

        // Estatísticas gerais
        $totalLocacoes = $todasLocacoes->count();
        $locacoesAtivas = $todasLocacoes->where('status', 'ativa')->count();
        $totalDespesas = $todasLocacoes->sum(function($locacao) {
            return $locacao->despesas->sum('valor');
        });
        $totalReceitas = $todasLocacoes->sum('valor_total');
        $totalCoanfitriao = $todasLocacoes->sum(function($locacao) {
            return round($locacao->valor_total * 0.3333, 2);
        });
        $lucroTotal = $totalReceitas - $totalCoanfitriao - $totalDespesas;

        // Dados dos últimos 6 meses para análise
        $ultimos6Meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $data = now()->subMonths($i);
            $mes = $data->format('m/Y');
            $locacoesMes = $todasLocacoes->filter(function($locacao) use ($data) {
                return Carbon::parse($locacao->data_pagamento)->format('Y-m') === $data->format('Y-m');
            });
            if ($locacoesMes->count() === 0) {
                continue; // Pula meses sem locação
            }
            $receitasMes = $locacoesMes->sum('valor_total');
            $coanfitriaoMes = $locacoesMes->sum(function($locacao) {
                return round($locacao->valor_total * 0.3333, 2);
            });
            $despesasMes = $locacoesMes->sum(function($locacao) {
                return $locacao->despesas->sum('valor');
            });
            $lucroMes = $receitasMes - $coanfitriaoMes - $despesasMes;
            
            $ultimos6Meses[$mes] = [
                'receitas' => $receitasMes,
                'coanfitriao' => $coanfitriaoMes,
                'despesas' => $despesasMes,
                'lucro' => $lucroMes,
                'quantidade' => $locacoesMes->count()
            ];
        }

        // Análises avançadas
        $analises = [];
        
        // Melhor e pior mês
        $melhorMes = null;
        $piorMes = null;
        $melhorLucro = -999999;
        $piorLucro = 999999;
        
        foreach ($ultimos6Meses as $mes => $dados) {
            if ($dados['lucro'] > $melhorLucro) {
                $melhorLucro = $dados['lucro'];
                $melhorMes = $mes;
            }
            if ($dados['lucro'] < $piorLucro) {
                $piorLucro = $dados['lucro'];
                $piorMes = $mes;
            }
        }
        
        $analises['melhor_mes'] = [
            'mes' => $melhorMes,
            'lucro' => $melhorLucro,
            'receitas' => $ultimos6Meses[$melhorMes]['receitas'] ?? 0,
            'despesas' => $ultimos6Meses[$melhorMes]['despesas'] ?? 0
        ];
        
        $analises['pior_mes'] = [
            'mes' => $piorMes,
            'lucro' => $piorLucro,
            'receitas' => $ultimos6Meses[$piorMes]['receitas'] ?? 0,
            'despesas' => $ultimos6Meses[$piorMes]['despesas'] ?? 0
        ];
        
        // Análise de tendência (últimos 3 meses vs 3 meses anteriores)
        $ultimos3Meses = array_slice($ultimos6Meses, -3);
        $anteriores3Meses = array_slice($ultimos6Meses, 0, 3);
        
        $mediaLucroUltimos3 = array_sum(array_column($ultimos3Meses, 'lucro')) / count($ultimos3Meses);
        $mediaLucroAnteriores3 = array_sum(array_column($anteriores3Meses, 'lucro')) / count($anteriores3Meses);
        
        $tendencia = $mediaLucroUltimos3 - $mediaLucroAnteriores3;
        $tendenciaPercentual = $mediaLucroAnteriores3 != 0 ? (($tendencia / $mediaLucroAnteriores3) * 100) : 0;
        
        $analises['tendencia'] = [
            'valor' => $tendencia,
            'percentual' => $tendenciaPercentual,
            'direcao' => $tendencia > 0 ? 'crescimento' : ($tendencia < 0 ? 'queda' : 'estavel'),
            'media_ultimos_3' => $mediaLucroUltimos3,
            'media_anteriores_3' => $mediaLucroAnteriores3
        ];
        
        // Análise de rentabilidade
        $rentabilidadeTotal = $totalReceitas > 0 ? (($lucroTotal / $totalReceitas) * 100) : 0;
        $rentabilidadeMedia = 0;
        $mesesComReceita = 0;
        
        foreach ($ultimos6Meses as $dados) {
            if ($dados['receitas'] > 0) {
                $rentabilidadeMes = (($dados['lucro'] / $dados['receitas']) * 100);
                $rentabilidadeMedia += $rentabilidadeMes;
                $mesesComReceita++;
            }
        }
        
        $rentabilidadeMedia = $mesesComReceita > 0 ? $rentabilidadeMedia / $mesesComReceita : 0;
        
        $analises['rentabilidade'] = [
            'total' => $rentabilidadeTotal,
            'media_mensal' => $rentabilidadeMedia,
            'meses_analisados' => $mesesComReceita
        ];

        // Dados para o gráfico comparativo mensal (mesmo que o método index)
        $resumoMensal = [];
        $resumoAnual = [];
        $mesesDisponiveis = [];
        $locacoesMensal = [];
        $coanfitriaoMensal = [];
        $despesasMensal = [];
        
        foreach ($todasLocacoes as $locacao) {
            $mes = Carbon::parse($locacao->data_pagamento)->format('m/Y');
            $ano = Carbon::parse($locacao->data_pagamento)->format('Y');
            $coanfitriao = round($locacao->valor_total * 0.3333, 2);
            $despesas = $locacao->despesas->sum('valor');
            $lucro = $locacao->valor_total - $coanfitriao - $despesas;
            $resumoMensal[$mes] = ($resumoMensal[$mes] ?? 0) + $lucro;
            $resumoAnual[$ano] = ($resumoAnual[$ano] ?? 0) + $lucro;
            $mesesDisponiveis[$mes] = Carbon::parse($locacao->data_pagamento)->format('Y-m');
            $locacoesMensal[$mes] = ($locacoesMensal[$mes] ?? 0) + $locacao->valor_total;
            $coanfitriaoMensal[$mes] = ($coanfitriaoMensal[$mes] ?? 0) + $coanfitriao;
            $despesasMensal[$mes] = ($despesasMensal[$mes] ?? 0) + $despesas;
        }
        
        // Garante que todos os meses presentes no resumoMensal estejam em todos os arrays
        foreach (array_keys($resumoMensal) as $mes) {
            $locacoesMensal[$mes] = $locacoesMensal[$mes] ?? 0;
            $coanfitriaoMensal[$mes] = $coanfitriaoMensal[$mes] ?? 0;
            $despesasMensal[$mes] = $despesasMensal[$mes] ?? 0;
        }
        
        ksort($resumoMensal);
        ksort($resumoAnual);
        krsort($mesesDisponiveis);
        ksort($locacoesMensal);
        ksort($coanfitriaoMensal);
        ksort($despesasMensal);

        return view('welcome', compact(
            'totalLocacoes',
            'locacoesAtivas', 
            'totalDespesas',
            'totalReceitas',
            'totalCoanfitriao',
            'lucroTotal',
            'ultimos6Meses',
            'analises',
            'resumoMensal',
            'resumoAnual',
            'mesesDisponiveis',
            'locacoesMensal',
            'coanfitriaoMensal',
            'despesasMensal'
        ));
    }

    public function index(Request $request)
    {
        $periodo = request('periodo', now()->format('Y-m'));
        $user = Auth::user();
        // Locações do usuário
        $query = $user->locacoes()->with('despesas', 'imovel');
        $query->whereYear('data_pagamento', substr($periodo, 0, 4))
              ->whereMonth('data_pagamento', substr($periodo, 5, 2));
        $locacoesUsuario = $query->get();
        // Locações de imóveis compartilhados
        $imoveisCompartilhadosIds = CompartilhamentoImovel::where('user_compartilhado_id', $user->id)->pluck('imovel_id');
        $locacoesCompartilhadas = \App\Models\Locacao::with('despesas', 'imovel')
            ->whereIn('imovel_id', $imoveisCompartilhadosIds)
            ->whereYear('data_pagamento', substr($periodo, 0, 4))
            ->whereMonth('data_pagamento', substr($periodo, 5, 2))
            ->get();
        // Unir e remover duplicatas
        $locacoes = $locacoesUsuario->merge($locacoesCompartilhadas)->unique('id');
        // Resumos
        $todasLocacoes = $user->locacoes()->with('despesas')->get()->merge(
            \App\Models\Locacao::with('despesas')
                ->whereIn('imovel_id', $imoveisCompartilhadosIds)
                ->get()
        )->unique('id');
        // Agrupar por mês/ano e calcular lucro (valor_total - coanfitrião - despesas)
        $resumoMensal = [];
        $resumoAnual = [];
        $mesesDisponiveis = [];
        $locacoesMensal = [];
        $coanfitriaoMensal = [];
        $despesasMensal = [];
        foreach ($todasLocacoes as $locacao) {
            $mes = Carbon::parse($locacao->data_pagamento)->format('m/Y');
            $ano = Carbon::parse($locacao->data_pagamento)->format('Y');
            $coanfitriao = round($locacao->valor_total * 0.3333, 2);
            $despesas = $locacao->despesas->sum('valor');
            $lucro = $locacao->valor_total - $coanfitriao - $despesas;
            // Só adiciona se houver pelo menos uma locação no mês
            if (!isset($locacoesMensal[$mes])) {
                $locacoesMensal[$mes] = 0;
            }
            $locacoesMensal[$mes] += $locacao->valor_total;
            $coanfitriaoMensal[$mes] = ($coanfitriaoMensal[$mes] ?? 0) + $coanfitriao;
            $despesasMensal[$mes] = ($despesasMensal[$mes] ?? 0) + $despesas;
            $resumoMensal[$mes] = ($resumoMensal[$mes] ?? 0) + $lucro;
            $resumoAnual[$ano] = ($resumoAnual[$ano] ?? 0) + $lucro;
            $mesesDisponiveis[$mes] = Carbon::parse($locacao->data_pagamento)->format('Y-m');
        }
        // Remove meses sem locação dos arrays de resumo
        foreach (array_keys($resumoMensal) as $mes) {
            if (($locacoesMensal[$mes] ?? 0) == 0) {
                unset($resumoMensal[$mes], $locacoesMensal[$mes], $coanfitriaoMensal[$mes], $despesasMensal[$mes], $mesesDisponiveis[$mes]);
            }
        }
        ksort($resumoMensal);
        ksort($resumoAnual);
        krsort($mesesDisponiveis); // mais recentes primeiro
        ksort($locacoesMensal);
        ksort($coanfitriaoMensal);
        ksort($despesasMensal);
        return view('locacoes.index', compact('locacoes', 'resumoMensal', 'resumoAnual', 'periodo', 'mesesDisponiveis', 'locacoesMensal', 'coanfitriaoMensal', 'despesasMensal'));
    }

    public function create(Request $request)
    {
        $imoveis = Imovel::where('user_id', auth()->id())->get();
        
        // Preencher dados se vierem da URL (do calendário)
        $dadosPreenchidos = [
            'imovel_id' => $request->get('imovel_id'),
            'data_inicio' => $request->get('data_inicio'),
            'data_fim' => $request->get('data_fim'),
            'nome' => $request->get('nome', 'Locação'), // Padrão: Locação
        ];
        
        // Calcular data de pagamento e valor total se vier do calendário
        if ($request->get('data_inicio') && $request->get('data_fim')) {
            $dataInicio = \Carbon\Carbon::parse($request->get('data_inicio'));
            $dataFim = \Carbon\Carbon::parse($request->get('data_fim'));
            
            // Data de pagamento: 1 dia após a data de início
            $dadosPreenchidos['data_pagamento'] = $dataInicio->copy()->addDay()->format('Y-m-d');
            
            // Calcular valor total: R$ 132 por dia
            $dias = $dataInicio->diffInDays($dataFim);
            $dadosPreenchidos['valor_total'] = $dias * 132;
        }
        
        return view('locacoes.create', compact('imoveis', 'dadosPreenchidos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'imovel_id' => 'required|exists:imoveis,id',
            'nome' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date',
            'data_pagamento' => 'required|date',
        ]);
        $locacao = Locacao::create($validated);
        $user = Auth::user();
        $locacao->users()->attach($user->id, ['papel' => 'anfitriao']);
        return redirect()->route('locacoes.show', $locacao->id);
    }

    public function show(Locacao $locacao)
    {
        $user = Auth::user();
        $isDono = $locacao->imovel && $locacao->imovel->user_id === $user->id;
        $isCompartilhado = $locacao->imovel && CompartilhamentoImovel::where('imovel_id', $locacao->imovel->id)->where('user_compartilhado_id', $user->id)->exists();
        if (!$isDono && !$isCompartilhado) {
            return response()->view('locacoes.nao-autorizado', [
                'mensagem' => 'Você não tem permissão para visualizar esta locação.'
            ], 403);
        }
        $locacao->load('despesas', 'imovel');
        $totalDespesas = $locacao->despesas->sum('valor');
        $coanfitriao = round($locacao->valor_total * 0.3333, 2);
        $saldo = $locacao->valor_total - $coanfitriao - $totalDespesas;
        return view('locacoes.show', [
            'locacao' => $locacao,
            'totalDespesas' => $totalDespesas,
            'coanfitriao' => $coanfitriao,
            'saldo' => $saldo
        ]);
    }

    public function edit(Locacao $locacao)
    {
        $user = Auth::user();
        $isDono = $locacao->imovel && $locacao->imovel->user_id === $user->id;
        $isCompartilhado = $locacao->imovel && CompartilhamentoImovel::where('imovel_id', $locacao->imovel->id)->where('user_compartilhado_id', $user->id)->exists();
        if (!$isDono && !$isCompartilhado) {
            return response()->view('locacoes.nao-autorizado', [
                'mensagem' => 'Você não tem permissão para editar esta locação.'
            ], 403);
        }
        return view('locacoes.edit', compact('locacao'));
    }

    public function update(Request $request, Locacao $locacao)
    {
        $validated = $request->validate([
            'nome' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date',
            'data_pagamento' => 'required|date',
        ]);
        $locacao->update($validated);
        return redirect()->route('locacoes.index');
    }

    public function destroy(Locacao $locacao)
    {
        $locacao->delete();
        return redirect()->route('locacoes.index');
    }
}
