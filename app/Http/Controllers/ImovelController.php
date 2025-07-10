<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CompartilhamentoImovel;
use App\Models\User;

class ImovelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imoveis = Imovel::where('user_id', Auth::id())->get();
        return view('imoveis.index', compact('imoveis'));
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
        $request->validate([
            'nome' => 'required|string|max:255',
            'email_compartilhado' => 'nullable|string',
        ]);
        $imovel = Imovel::create([
            'user_id' => Auth::id(),
            'nome' => $request->nome,
        ]);
        $erros = [];
        if ($request->filled('email_compartilhado')) {
            $emails = array_filter(array_map('trim', explode(',', $request->email_compartilhado)));
            foreach ($emails as $email) {
                $userCompartilhado = User::where('email', $email)->first();
                if ($userCompartilhado) {
                    CompartilhamentoImovel::create([
                        'imovel_id' => $imovel->id,
                        'user_imovel_id' => Auth::id(),
                        'user_compartilhado_id' => $userCompartilhado->id,
                    ]);
                } else {
                    $erros[] = $email;
                }
            }
            if (count($erros) === count($emails)) {
                $imovel->delete();
                return redirect()->route('imoveis.index')->withErrors(['email_compartilhado' => 'Nenhum dos e-mails foi encontrado: ' . implode(', ', $erros)]);
            } elseif (count($erros) > 0) {
                return redirect()->route('imoveis.index')->with('success', 'Imóvel cadastrado e compartilhado com sucesso, mas os seguintes e-mails não foram encontrados: ' . implode(', ', $erros));
            } else {
                return redirect()->route('imoveis.index')->with('success', 'Imóvel cadastrado e compartilhado com sucesso!');
            }
        }
        return redirect()->route('imoveis.index')->with('success', 'Imóvel cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imovel $imovel)
    {
        Log::info('Update chamado', [
            'id' => $imovel->id,
            'nome_request' => $request->nome,
            'imovel_nome_antes' => $imovel->nome
        ]);
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        $imovel->update([
            'nome' => $request->nome,
        ]);
        return redirect()->route('imoveis.index')->with('success', 'Imóvel atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imovel $imovel)
    {
        $imovel->delete();
        return redirect()->route('imoveis.index')->with('success', 'Imóvel excluído com sucesso!');
    }

    public function adicionarCompartilhamento(Request $request, Imovel $imovel)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $userCompartilhado = User::where('email', $request->email)->first();
        if (!$userCompartilhado) {
            return redirect()->route('imoveis.index')->withErrors(['email' => 'E-mail não encontrado.']);
        }
        // Evitar duplicidade
        $existe = $imovel->compartilhamentos()->where('user_compartilhado_id', $userCompartilhado->id)->exists();
        if ($existe) {
            return redirect()->route('imoveis.index')->withErrors(['email' => 'Este usuário já possui acesso a este imóvel.']);
        }
        CompartilhamentoImovel::create([
            'imovel_id' => $imovel->id,
            'user_imovel_id' => $imovel->user_id,
            'user_compartilhado_id' => $userCompartilhado->id,
        ]);
        return redirect()->route('imoveis.index')->with('success', 'Compartilhamento adicionado com sucesso!');
    }

    public function removerCompartilhamento(CompartilhamentoImovel $compartilhamento)
    {
        $compartilhamento->delete();
        return redirect()->route('imoveis.index')->with('success', 'Compartilhamento removido com sucesso!');
    }
}
