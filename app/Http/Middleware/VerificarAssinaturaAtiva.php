<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarAssinaturaAtiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $assinaturaAtiva = $user->assinaturaAtiva;

        // Se não tem assinatura ativa, redireciona para a página de assinatura
        if (!$assinaturaAtiva || $assinaturaAtiva->status !== 'ativa') {
            // Armazena a URL atual para redirecionar após a assinatura
            session(['url.intended' => $request->url()]);
            
            return redirect()->route('assinatura')->with('warning', 'Você precisa de uma assinatura ativa para acessar esta funcionalidade.');
        }

        return $next($request);
    }
}
