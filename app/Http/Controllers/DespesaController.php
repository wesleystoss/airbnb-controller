<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Locacao;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Despesa::with('locacao')->get();
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
            'locacao_id' => 'required|exists:locacaos,id',
            'descricao' => 'required|string',
            'valor' => 'required|numeric',
        ]);
        $despesa = Despesa::create($validated);
        return response()->json($despesa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Despesa $despesa)
    {
        return $despesa->load('locacao');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Despesa $despesa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Despesa $despesa)
    {
        $validated = $request->validate([
            'locacao_id' => 'sometimes|exists:locacaos,id',
            'descricao' => 'sometimes|string',
            'valor' => 'sometimes|numeric',
        ]);
        $despesa->update($validated);
        return response()->json($despesa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Despesa $despesa)
    {
        $despesa->delete();
        return response()->json(null, 204);
    }
}
