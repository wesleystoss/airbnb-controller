<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\Locacao;
use Illuminate\Http\RedirectResponse;

class DespesaWebController extends Controller
{
    public function create(Locacao $locacao)
    {
        return view('despesas.create', compact('locacao'));
    }

    public function store(Request $request, Locacao $locacao)
    {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'valor' => 'required|numeric',
        ]);
        $validated['locacao_id'] = $locacao->id;
        Despesa::create($validated);
        
        // Se o botão "Criar e Continuar" foi clicado, redireciona para o formulário de nova despesa
        if ($request->has('criar_e_continuar')) {
            return redirect()->route('despesas.create', $locacao->id)
                           ->with('success', 'Despesa criada com sucesso!');
        }
        
        return redirect()->route('locacoes.show', $locacao->id);
    }

    public function edit(Despesa $despesa)
    {
        return view('despesas.edit', compact('despesa'));
    }

    public function update(Request $request, Despesa $despesa)
    {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'valor' => 'required|numeric',
        ]);
        $despesa->update($validated);
        return redirect()->route('locacoes.show', $despesa->locacao_id);
    }

    public function destroy(Despesa $despesa)
    {
        $locacaoId = $despesa->locacao_id;
        $despesa->delete();
        return redirect()->route('locacoes.show', $locacaoId);
    }
}
