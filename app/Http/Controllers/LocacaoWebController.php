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
        krsort($mesesDisponiveis); // mais recentes primeiro
        ksort($locacoesMensal);
        ksort($coanfitriaoMensal);
        ksort($despesasMensal);
        return view('locacoes.index', compact('locacoes', 'resumoMensal', 'resumoAnual', 'periodo', 'mesesDisponiveis', 'locacoesMensal', 'coanfitriaoMensal', 'despesasMensal'));
    }

    public function create()
    {
        $imoveis = Imovel::where('user_id', auth()->id())->get();
        return view('locacoes.create', compact('imoveis'));
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
