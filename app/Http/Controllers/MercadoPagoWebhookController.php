<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Loga o payload recebido para debug
        Log::info('Webhook Mercado Pago recebido:', $request->all());

        // Aqui você pode tratar os eventos conforme sua necessidade
        // Exemplo: pagamento aprovado, recusado, etc.

        return response()->json(['status' => 'ok']);
    }
} 