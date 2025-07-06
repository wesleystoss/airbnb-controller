<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class LocacaoWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $locacoes = Locacao::with('despesas')->orderBy('data_inicio')->get();
        // Agrupar por mês/ano e calcular lucro (valor_total - coanfitrião - despesas)
        $resumoMensal = [];
        $resumoAnual = [];
        foreach ($locacoes as $locacao) {
            $mes = Carbon::parse($locacao->data_inicio)->format('m/Y');
            $ano = Carbon::parse($locacao->data_inicio)->format('Y');
            $coanfitriao = round($locacao->valor_total * 0.3333, 2);
            $despesas = $locacao->despesas->sum('valor');
            $lucro = $locacao->valor_total - $coanfitriao - $despesas;
            $resumoMensal[$mes] = ($resumoMensal[$mes] ?? 0) + $lucro;
            $resumoAnual[$ano] = ($resumoAnual[$ano] ?? 0) + $lucro;
        }
        ksort($resumoMensal);
        ksort($resumoAnual);
        return view('locacoes.index', compact('locacoes', 'resumoMensal', 'resumoAnual'));
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
        Locacao::create($validated);
        return redirect()->route('locacoes.index');
    }

    public function show(Locacao $locacao)
    {
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
