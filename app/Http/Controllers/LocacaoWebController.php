<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LocacaoWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $periodo = request('periodo', now()->format('Y-m'));
        
        // Query para locações com filtro por período
        $query = Auth::user()->locacoes()->with('despesas')->orderBy('data_inicio', 'desc');
        $query->whereYear('data_inicio', substr($periodo, 0, 4))
              ->whereMonth('data_inicio', substr($periodo, 5, 2));
        
        $locacoes = $query->paginate(10)->withQueryString();
        
        // Query separada para calcular resumos (todas as locações do usuário, sem paginação)
        $todasLocacoes = Auth::user()->locacoes()->with('despesas')->get();
        
        // Agrupar por mês/ano e calcular lucro (valor_total - coanfitrião - despesas)
        $resumoMensal = [];
        $resumoAnual = [];
        $mesesDisponiveis = [];
        $locacoesMensal = [];
        $coanfitriaoMensal = [];
        $despesasMensal = [];
        foreach ($todasLocacoes as $locacao) {
            $mes = Carbon::parse($locacao->data_inicio)->format('m/Y');
            $ano = Carbon::parse($locacao->data_inicio)->format('Y');
            $coanfitriao = round($locacao->valor_total * 0.3333, 2);
            $despesas = $locacao->despesas->sum('valor');
            $lucro = $locacao->valor_total - $coanfitriao - $despesas;
            $resumoMensal[$mes] = ($resumoMensal[$mes] ?? 0) + $lucro;
            $resumoAnual[$ano] = ($resumoAnual[$ano] ?? 0) + $lucro;
            $mesesDisponiveis[$mes] = Carbon::parse($locacao->data_inicio)->format('Y-m');
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
        return view('locacoes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);
        $locacao = Locacao::create($validated);
        $user = Auth::user();
        $locacao->users()->attach($user->id, ['papel' => 'anfitriao']);
        return redirect()->route('locacoes.show', $locacao->id);
    }

    public function show(Locacao $locacao)
    {
        if (!Auth::user()->locacoes()->where('locacao_id', $locacao->id)->exists()) {
            return response()->view('locacoes.nao-autorizado', [
                'mensagem' => 'Locação não localizada para este usuário.'
            ], 403);
        }
        $locacao->load('despesas');
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
        return view('locacoes.edit', compact('locacao'));
    }

    public function update(Request $request, Locacao $locacao)
    {
        $validated = $request->validate([
            'nome' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
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
