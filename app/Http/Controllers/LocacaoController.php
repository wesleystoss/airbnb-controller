<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Models\Despesa;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Locacao::with('despesas')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);
        $locacao = Locacao::create($validated);
        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Locacao $locacao)
    {
        return $locacao->load('despesas');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locacao $locacao)
    {
        $validated = $request->validate([
            'nome' => 'sometimes|string',
            'valor_total' => 'sometimes|numeric',
            'data_inicio' => 'sometimes|date',
            'data_fim' => 'sometimes|date',
        ]);
        $locacao->update($validated);
        return response()->json($locacao);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locacao $locacao)
    {
        $locacao->delete();
        return response()->json(null, 204);
    }

    public function saldo(Locacao $locacao)
    {
        $totalDespesas = $locacao->despesas()->sum('valor');
        $saldo = $locacao->valor_total - $totalDespesas;
        return response()->json([
            'valor_total' => $locacao->valor_total,
            'total_despesas' => $totalDespesas,
            'saldo' => $saldo
        ]);
    }
}
