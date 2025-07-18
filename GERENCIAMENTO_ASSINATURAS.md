# 📋 Sistema de Gerenciamento de Assinaturas - Airbnb Controle

## 📖 Índice
1. [Visão Geral](#visão-geral)
2. [Estrutura do Sistema](#estrutura-do-sistema)
3. [Gerenciamento de Assinaturas](#gerenciamento-de-assinaturas)
4. [Reprocessamento de Transações](#reprocessamento-de-transações)
5. [Comandos Úteis](#comandos-úteis)
6. [Monitoramento e Logs](#monitoramento-e-logs)
7. [Troubleshooting](#troubleshooting)
8. [Exemplos Práticos](#exemplos-práticos)

---

## 🎯 Visão Geral

O sistema de assinaturas do Airbnb Controle gerencia automaticamente o ciclo de vida das assinaturas baseado nos webhooks do Mercado Pago. Quando um pagamento é processado, o sistema automaticamente:

- ✅ Cria/atualiza assinaturas
- 🔄 Gerencia status (ativa, cancelada, expirada)
- 📊 Registra histórico de transações
- 🚨 Notifica sobre problemas
- 🔒 Protege rotas com middleware de assinatura ativa
- 🎯 Redireciona usuários sem assinatura para página de assinatura
- 📱 Interface integrada no painel de controle

---

## 🏗️ Estrutura do Sistema

### Tabela `assinaturas`
```sql
CREATE TABLE assinaturas (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    status ENUM('ativa', 'cancelada', 'expirada', 'pendente') DEFAULT 'pendente',
    data_inicio DATE NOT NULL,
    data_expiracao DATE NOT NULL,
    payment_id VARCHAR(255) NULL,
    valor DECIMAL(10,2) DEFAULT 39.90,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_expiracao (data_expiracao),
    INDEX idx_payment (payment_id)
);
```

### Status das Assinaturas
- **`ativa`**: Assinatura válida e em vigor
- **`cancelada`**: Assinatura cancelada ou pagamento rejeitado
- **`expirada`**: Assinatura vencida automaticamente
- **`pendente`**: Aguardando confirmação de pagamento

---

## 🔧 Gerenciamento de Assinaturas

### 1. Verificar Assinatura de um Usuário

```bash
# Via Artisan Tinker
php artisan tinker

# Buscar assinatura ativa
>>> $user = App\Models\User::find(1);
>>> $assinatura = $user->assinaturaAtiva;
>>> echo "Status: " . $assinatura->status;
>>> echo "Expira em: " . $assinatura->data_expiracao;

# Ou usar método estático
>>> $assinatura = App\Models\Assinatura::ativaDoUsuario(1);
```

### 2. Criar Assinatura Manualmente

```php
// Via Tinker
$user = App\Models\User::find(1);
$assinatura = App\Models\Assinatura::create([
    'user_id' => $user->id,
    'status' => 'ativa',
    'data_inicio' => now(),
    'data_expiracao' => now()->addMonth(),
    'valor' => 39.90
]);
```

### 3. Atualizar Status de Assinatura

```php
// Ativar assinatura
$assinatura->ativar();

// Cancelar assinatura
$assinatura->cancelar();

// Expirar assinatura
$assinatura->expirar();

// Renovar assinatura
$assinatura->renovar();
```

### 4. Verificar Assinaturas Expiradas

```bash
# Executar verificação manual
php artisan assinaturas:verificar-expiradas
```

**Saída esperada:**
```
🔍 Verificando assinaturas expiradas...
📅 Encontradas 2 assinatura(s) expirada(s).
🔄 Atualizando assinatura ID: 1 - Usuário: user@example.com
🔄 Atualizando assinatura ID: 3 - Usuário: another@example.com
✅ Processo concluído!
```

---

## 🔄 Reprocessamento de Transações

### 1. Reenviar Webhook Manualmente

```bash
# Reenviar webhook para um pagamento específico
php artisan webhook:resend 118948840058
```

**Saída esperada:**
```
Reenviando webhook para o pagamento: 118948840058
✅ Webhook reenviado com sucesso!
Status: 200
Resposta: {"status":"ok"}
```

### 2. Verificar Status de Pagamento

```bash
# Buscar detalhes de um pagamento
curl -X GET "https://api.mercadopago.com/v1/payments/118948840058" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 3. Reprocessar Pagamento Rejeitado

```php
// Via Tinker - Criar nova tentativa
$user = App\Models\User::where('email', 'user@example.com')->first();
$assinatura = App\Models\Assinatura::create([
    'user_id' => $user->id,
    'status' => 'pendente',
    'data_inicio' => now(),
    'data_expiracao' => now()->addDays(7), // Período de tentativa
    'payment_id' => 'NOVO_PAYMENT_ID',
    'valor' => 39.90
]);
```

---

## 🛠️ Comandos Úteis

### Comandos Artisan Disponíveis

```bash
# Verificar assinaturas expiradas
php artisan assinaturas:verificar-expiradas

# Reenviar webhook
php artisan webhook:resend {payment_id}

# Listar todas as rotas
php artisan route:list | grep webhook

# Limpar cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Queries Úteis no Tinker

```php
// Buscar todas as assinaturas ativas
App\Models\Assinatura::where('status', 'ativa')->get();

// Buscar assinaturas que expiram em 7 dias
App\Models\Assinatura::where('status', 'ativa')
    ->where('data_expiracao', '<=', now()->addDays(7))
    ->get();

// Buscar assinaturas por payment_id
App\Models\Assinatura::where('payment_id', '118948840058')->first();

// Contar assinaturas por status
App\Models\Assinatura::selectRaw('status, count(*) as total')
    ->groupBy('status')
    ->get();
```

---

## 📊 Monitoramento e Logs

### Localização dos Logs

```bash
# Logs do Laravel
tail -f storage/logs/laravel.log

# Filtrar logs de webhook
grep "Webhook Mercado Pago" storage/logs/laravel.log

# Filtrar logs de assinatura
grep "Assinatura" storage/logs/laravel.log
```

### Logs Importantes

```log
# Webhook recebido
[2025-07-18 00:17:01] local.INFO: Webhook Mercado Pago recebido: {...}

# Pagamento aprovado
[2025-07-18 00:17:01] local.INFO: ✅ Pagamento aprovado: {"payment_id":118948840058}

# Assinatura criada
[2025-07-18 00:17:01] local.INFO: ✅ Assinatura criada/ativada com sucesso

# Pagamento rejeitado
[2025-07-18 00:17:01] local.INFO: ❌ Pagamento rejeitado: {"reason":"Pagamento rejeitado por risco"}

# Assinatura expirada
[2025-07-18 00:17:01] local.INFO: 📅 Assinatura expirada automaticamente
```

### Monitoramento de Status

```bash
# Verificar status do webhook
curl -X POST https://seu-dominio.com/api/webhook/mercadopago \
  -H "Content-Type: application/json" \
  -d '{"test": "webhook"}' \
  -v

# Verificar se a rota está funcionando
curl -X GET https://seu-dominio.com/webhook/mercadopago/test
```

---

## 🔍 Troubleshooting

### Problemas Comuns

#### 1. Webhook não está sendo recebido
```bash
# Verificar se a rota está registrada
php artisan route:list | grep webhook

# Verificar logs de erro
tail -f storage/logs/laravel.log | grep ERROR

# Testar webhook manualmente
php artisan webhook:resend {payment_id}
```

#### 2. Assinatura não está sendo criada
```bash
# Verificar se o usuário existe
php artisan tinker
>>> App\Models\User::where('email', 'user@example.com')->first();

# Verificar logs de processamento
grep "Processando pagamento" storage/logs/laravel.log
```

#### 3. Assinatura não está sendo atualizada
```bash
# Verificar se a migration foi executada
php artisan migrate:status

# Verificar estrutura da tabela
php artisan tinker
>>> Schema::getColumnListing('assinaturas');
```

#### 4. Erro 419 (CSRF Token)
```bash
# Verificar se a rota está em routes/api.php
cat routes/api.php | grep webhook

# Limpar cache de rotas
php artisan route:clear
```

### Soluções para Problemas Específicos

#### Email mascarado no webhook
```php
// O Mercado Pago mascara emails por segurança
// Solução: Usar external_reference ou buscar usuário de outra forma
$user = User::where('email', $email)->first();
// ou
$user = User::where('id', $externalReference)->first();
```

#### Pagamento duplicado
```php
// Verificar se já existe assinatura para este payment_id
$assinaturaExistente = Assinatura::where('payment_id', $paymentId)->first();
if ($assinaturaExistente) {
    Log::info('Pagamento já processado', ['payment_id' => $paymentId]);
    return;
}
```

#### Assinatura não expira automaticamente
```bash
# Configurar cron job para executar diariamente
# Adicionar ao crontab:
0 2 * * * cd /path/to/project && php artisan assinaturas:verificar-expiradas
```

---

## 📝 Exemplos Práticos

### Exemplo 1: Reprocessar Pagamento Aprovado

```bash
# 1. Identificar payment_id
payment_id = "118948840058"

# 2. Reenviar webhook
php artisan webhook:resend 118948840058

# 3. Verificar se a assinatura foi criada
php artisan tinker
>>> $assinatura = App\Models\Assinatura::where('payment_id', '118948840058')->first();
>>> echo "Status: " . $assinatura->status;
>>> echo "Usuário: " . $assinatura->user->email;
```

### Exemplo 2: Corrigir Assinatura Expirada

```bash
# 1. Verificar assinaturas expiradas
php artisan assinaturas:verificar-expiradas

# 2. Renovar assinatura manualmente
php artisan tinker
>>> $assinatura = App\Models\Assinatura::find(1);
>>> $assinatura->renovar();
>>> echo "Nova expiração: " . $assinatura->data_expiracao;
```

### Exemplo 3: Investigar Pagamento Rejeitado

```bash
# 1. Verificar logs do webhook
grep "118481895101" storage/logs/laravel.log

# 2. Buscar detalhes do pagamento
curl -X GET "https://api.mercadopago.com/v1/payments/118481895101" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"

# 3. Verificar assinatura criada
php artisan tinker
>>> $assinatura = App\Models\Assinatura::where('payment_id', '118481895101')->first();
>>> echo "Status: " . $assinatura->status;
```

### Exemplo 4: Relatório de Assinaturas

```php
// Via Tinker - Gerar relatório
$relatorio = [
    'total_ativas' => App\Models\Assinatura::where('status', 'ativa')->count(),
    'total_canceladas' => App\Models\Assinatura::where('status', 'cancelada')->count(),
    'total_expiradas' => App\Models\Assinatura::where('status', 'expirada')->count(),
    'expirando_em_7_dias' => App\Models\Assinatura::where('status', 'ativa')
        ->where('data_expiracao', '<=', now()->addDays(7))
        ->count()
];

print_r($relatorio);
```

---

## 🚀 Configuração de Produção

### 1. Configurar Cron Job

```bash
# Editar crontab
crontab -e

# Adicionar linha para verificar assinaturas diariamente às 2h
0 2 * * * cd /path/to/your/project && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
```

### 2. Configurar Monitoramento

```bash
# Script para monitorar webhooks
#!/bin/bash
LOG_FILE="/path/to/project/storage/logs/laravel.log"
WEBHOOK_COUNT=$(grep "Webhook Mercado Pago recebido" $LOG_FILE | wc -l)
echo "Webhooks recebidos hoje: $WEBHOOK_COUNT"
```

### 3. Backup de Dados

```bash
# Backup da tabela de assinaturas
mysqldump -u username -p database_name assinaturas > backup_assinaturas_$(date +%Y%m%d).sql

# Restaurar backup
mysql -u username -p database_name < backup_assinaturas_20250718.sql
```

---

## 📞 Suporte

### Contatos Importantes
- **Mercado Pago**: [Suporte Mercado Pago](https://www.mercadopago.com.br/developers/support)
- **Logs do Sistema**: `storage/logs/laravel.log`
- **Documentação**: Este arquivo

### Informações Úteis
- **URL do Webhook**: `https://seu-dominio.com/api/webhook/mercadopago`
- **Access Token**: Configurado em `.env` como `MERCADOPAGO_ACCESS_TOKEN`
- **Valor da Assinatura**: R$ 39,90 (configurável no modelo)

---

## 🔄 **Cobrança Recorrente Automática**

### **Como Funciona**

O sistema agora suporta cobrança recorrente mensal automática através do Mercado Pago Subscriptions:

1. **Criação da Assinatura**: Usuário clica em "Comprar agora" → Sistema cria assinatura recorrente no Mercado Pago
2. **Autorização**: Usuário autoriza a cobrança recorrente no ambiente seguro do Mercado Pago
3. **Cobrança Automática**: Mercado Pago cobra automaticamente R$ 39,90 todo mês
4. **Webhook**: Sistema recebe notificação e atualiza status da assinatura
5. **Renovação**: Comando automático renova assinaturas ativas

### **Configuração da Assinatura Recorrente**

```php
// Em CheckoutController.php
$subscription = [
    'reason' => 'Assinatura Airbnb Controle',
    'auto_recurring' => [
        'frequency' => 1,
        'frequency_type' => 'months',
        'transaction_amount' => 39.90,
        'currency_id' => 'BRL'
    ],
    'back_url' => 'https://seu-dominio.com/assinatura',
    'external_reference' => $user->id,
    'payer_email' => $user->email
];
```

### **Status das Assinaturas Recorrentes**

- **`authorized`**: Assinatura ativa e autorizada para cobrança
- **`pending`**: Aguardando confirmação do usuário
- **`cancelled`**: Assinatura cancelada
- **`paused`**: Assinatura pausada temporariamente

### **Webhooks de Assinatura Recorrente**

O sistema processa webhooks do tipo `subscription_preapproval`:

```php
// Em MercadoPagoWebhookController.php
if ($request->has('type') && $request->type === 'subscription_preapproval') {
    $subscriptionId = $request->data['id'] ?? null;
    $subscriptionDetails = $this->getSubscriptionDetails($subscriptionId);
    $this->processSubscriptionStatus($subscriptionDetails);
}
```

### **Renovação Automática**

O comando `assinaturas:verificar-expiradas` agora também:

1. **Verifica assinaturas expiradas** e marca como expiradas
2. **Renova assinaturas recorrentes** que expiram em até 3 dias
3. **Sincroniza com Mercado Pago** para verificar status real

### **Cron Job Configurado**

```bash
# Executa diariamente às 2h da manhã
0 2 * * * cd /home/wesley/Documentos/Projetos/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
```

### **Cancelamento de Assinatura**

Usuários podem cancelar suas assinaturas:

1. **Via Interface**: Botão "Cancelar Assinatura" na página de assinatura
2. **Via API**: Cancela tanto no Mercado Pago quanto localmente
3. **Via Webhook**: Sistema detecta cancelamento automático

### **Vantagens da Cobrança Recorrente**

✅ **Automatização completa** - Sem intervenção manual
✅ **Maior retenção** - Usuários não precisam lembrar de renovar
✅ **Receita previsível** - Cobrança mensal garantida
✅ **Menos falhas** - Reduz pagamentos perdidos
✅ **Experiência melhor** - Usuário não precisa reautorizar

### **Monitoramento de Assinaturas Recorrentes**

```bash
# Verificar assinaturas ativas
php artisan tinker
>>> App\Models\Assinatura::where('status', 'ativa')->get();

# Verificar próximas renovações
>>> App\Models\Assinatura::where('status', 'ativa')
    ->where('data_expiracao', '<=', now()->addDays(7))
    ->get();

# Executar verificação manual
php artisan assinaturas:verificar-expiradas
```

### **Troubleshooting de Assinaturas Recorrentes**

#### **Assinatura não está sendo renovada**
```bash
# Verificar logs
tail -f storage/logs/laravel.log | grep "renovada"

# Verificar status no Mercado Pago
curl -X GET "https://api.mercadopago.com/preapproval/{SUBSCRIPTION_ID}" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

#### **Webhook não está sendo recebido**
```bash
# Verificar se a URL está configurada no Mercado Pago
# URL: https://seu-dominio.com/api/webhook/mercadopago
# Eventos: subscription_preapproval
```

#### **Cron job não está executando**
```bash
# Verificar se o cron está ativo
sudo systemctl status cron

# Verificar logs do cron
sudo tail -f /var/log/cron.log
```

---

## 🎯 **Sistema de Tentativas de Cobrança (5 Tentativas)**

### **Como Funciona**

O sistema implementa uma estratégia robusta de 5 tentativas de cobrança antes de cancelar automaticamente:

1. **1ª Falha** → Tenta novamente amanhã
2. **2ª Falha** → Tenta novamente amanhã  
3. **3ª Falha** → Tenta novamente amanhã
4. **4ª Falha** → Tenta novamente amanhã
5. **5ª Falha** → **Cancela automaticamente** a assinatura

### **Campos de Controle**

```sql
-- Novos campos na tabela assinaturas
tentativas_cobranca INT DEFAULT 0
ultima_tentativa_cobranca TIMESTAMP NULL
proxima_tentativa_cobranca TIMESTAMP NULL
status_cobranca ENUM('sucesso', 'falha', 'pendente') DEFAULT 'pendente'
motivo_falha TEXT NULL
```

### **Comando de Processamento**

```bash
# Executa diariamente às 8h da manhã
php artisan assinaturas:processar-tentativas-cobranca
```

**Cron Job configurado:**
```bash
0 8 * * * cd /home/wesley/Documentos/Projetos/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1
```

### **Métodos do Modelo Assinatura**

```php
// Registra falha de cobrança
$assinatura->registrarFalhaCobranca($motivo);

// Registra sucesso na cobrança
$assinatura->registrarSucessoCobranca();

// Cancela por múltiplas falhas
$assinatura->cancelarPorFalhas();

// Verifica se deve tentar cobrar
$assinatura->deveTentarCobrar();

// Busca assinaturas com tentativas pendentes
Assinatura::comTentativasPendentes();

// Busca assinaturas com falhas
Assinatura::comFalhasCobranca();
```

### **Processamento de Webhooks**

O sistema processa automaticamente:

- **`payment.rejected`** → Registra falha na assinatura existente
- **`payment.approved`** → Registra sucesso e zera tentativas
- **`subscription_preapproval.cancelled`** → Cancela assinatura local

### **Fluxo de Tentativas**

```php
// 1. Webhook recebe falha
$assinatura->registrarFalhaCobranca('Saldo insuficiente');

// 2. Sistema agenda próxima tentativa
$assinatura->proxima_tentativa_cobranca = now()->addDay();

// 3. Comando diário processa tentativas pendentes
php artisan assinaturas:processar-tentativas-cobranca

// 4. Se chegou a 5 tentativas, cancela automaticamente
if ($assinatura->tentativas_cobranca >= 5) {
    $assinatura->cancelarPorFalhas();
}
```

### **Monitoramento de Tentativas**

```bash
# Verificar assinaturas com falhas
php artisan tinker
>>> App\Models\Assinatura::comFalhasCobranca()->get();

# Verificar próximas tentativas
>>> App\Models\Assinatura::comTentativasPendentes()->get();

# Verificar tentativas por usuário
>>> $user = App\Models\User::find(1);
>>> $assinatura = $user->assinaturaAtiva;
>>> echo "Tentativas: " . $assinatura->tentativas_cobranca;
>>> echo "Próxima tentativa: " . $assinatura->proxima_tentativa_cobranca;
```

### **Logs de Tentativas**

```log
# Falha registrada
[2025-07-18 10:00:00] local.INFO: ❌ Falha de cobrança registrada em assinatura existente

# Tentativa processada
[2025-07-18 08:00:00] local.INFO: 🔄 Tentativa 2/5 para assinatura ID: 1

# Assinatura cancelada
[2025-07-18 08:00:00] local.INFO: 🚫 Assinatura cancelada por múltiplas falhas de cobrança
```

### **Vantagens do Sistema**

✅ **Controle total** - 5 tentativas antes de cancelar
✅ **Flexibilidade** - Intervalo de 1 dia entre tentativas
✅ **Automatização** - Processamento diário automático
✅ **Rastreabilidade** - Logs detalhados de cada tentativa
✅ **Recuperação** - Sucesso em qualquer tentativa zera contador

### **Configuração de Produção**

```bash
# Verificar se cron está ativo
sudo systemctl status cron

# Verificar logs do cron
sudo tail -f /var/log/cron.log

# Testar comando manualmente
php artisan assinaturas:processar-tentativas-cobranca
```

### **Troubleshooting**

#### **Comando não está executando**
```bash
# Verificar permissões
chmod +x /home/wesley/Documentos/Projetos/airbnb/artisan

# Verificar logs
tail -f storage/logs/laravel.log | grep "tentativa"
```

#### **Assinatura não está sendo processada**
```bash
# Verificar se está na lista de pendentes
php artisan tinker
>>> App\Models\Assinatura::comTentativasPendentes()->count();
```

#### **Múltiplas tentativas no mesmo dia**
```bash
# Verificar configuração do cron
crontab -l

# Verificar se não há jobs duplicados
ps aux | grep artisan
```

---

## 🔒 **Middleware de Verificação de Assinatura**

### **Como Funciona**

O sistema implementa um middleware que verifica automaticamente se o usuário tem uma assinatura ativa antes de permitir acesso às funcionalidades:

1. **Usuário tenta acessar** uma funcionalidade protegida
2. **Middleware verifica** se tem assinatura ativa
3. **Se não tem** → Redireciona para página de assinatura
4. **Se tem** → Permite acesso normalmente
5. **Após assinar** → Redireciona de volta para onde estava

### **Middleware Implementado**

```php
// app/Http/Middleware/VerificarAssinaturaAtiva.php
class VerificarAssinaturaAtiva
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $assinaturaAtiva = $user->assinaturaAtiva;

        if (!$assinaturaAtiva || $assinaturaAtiva->status !== 'ativa') {
            session(['url.intended' => $request->url()]);
            return redirect()->route('assinatura')
                ->with('warning', 'Você precisa de uma assinatura ativa para acessar esta funcionalidade.');
        }

        return $next($request);
    }
}
```

### **Registro do Middleware**

```php
// bootstrap/app.php
$middleware->alias([
    'assinatura.ativa' => \App\Http\Middleware\VerificarAssinaturaAtiva::class,
]);
```

### **Rotas Protegidas**

```php
// routes/web.php
Route::middleware(['auth', 'assinatura.ativa'])->group(function () {
    // Locações
    Route::get('/locacoes', [LocacaoWebController::class, 'index']);
    Route::get('/locacoes/create', [LocacaoWebController::class, 'create']);
    // ... outras rotas de locações
    
    // Despesas
    Route::get('/locacoes/{locacao}/despesas/create', [DespesaWebController::class, 'create']);
    // ... outras rotas de despesas
});
```

### **Menu "Minha Assinatura"**

Adicionado no dropdown do usuário no header:

```html
<a href="{{ route('assinatura') }}" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50/80 transition-colors duration-200">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Minha Assinatura
</a>
```

### **Fluxo de Redirecionamento**

1. **Usuário sem assinatura** tenta acessar `/locacoes`
2. **Middleware intercepta** e salva a URL atual
3. **Redireciona para** `/assinatura` com mensagem de warning
4. **Usuário faz assinatura** no Mercado Pago
5. **Retorna para** `/assinatura?success=true`
6. **Sistema mostra** mensagem de sucesso
7. **Link "Voltar"** aparece para redirecionar para `/locacoes`

### **Mensagens do Sistema**

```php
// Redirecionamento por falta de assinatura
->with('warning', 'Você precisa de uma assinatura ativa para acessar esta funcionalidade.')

// Retorno bem-sucedido do Mercado Pago
@if(request('success') && auth()->check() && auth()->user()->assinaturaAtiva)
    ✅ Assinatura ativada com sucesso! Você já pode usar todas as funcionalidades.
@endif
```

### **Funcionalidades Protegidas**

✅ **Locações** - Todas as rotas de CRUD
✅ **Despesas** - Todas as rotas de CRUD
✅ **Imóveis** - Todas as rotas de CRUD (incluindo compartilhamento)
✅ **Calendário** - Todas as rotas (index, show, sync, update-ical)
✅ **Página inicial** - Painel de controle

### **Funcionalidades Livres**

✅ **Página de assinatura** - `/assinatura`
✅ **Perfil do usuário** - `/profile`
✅ **Login/Registro** - `/login`, `/register`
✅ **Checkout** - `/checkout`, `/checkout/pagar`

### **Teste do Sistema**

```bash
# 1. Usuário sem assinatura tenta acessar funcionalidades protegidas
curl -H "Cookie: laravel_session=..." https://seu-dominio.com/locacoes
curl -H "Cookie: laravel_session=..." https://seu-dominio.com/imoveis
curl -H "Cookie: laravel_session=..." https://seu-dominio.com/calendar
curl -H "Cookie: laravel_session=..." https://seu-dominio.com/

# 2. Deve ser redirecionado para
https://seu-dominio.com/assinatura

# 3. Após assinar, retorna para
https://seu-dominio.com/assinatura?success=true

# 4. Pode clicar em "Voltar" para ir para a página original
# ou ser redirecionado automaticamente após 3 segundos
```

### **Vantagens do Sistema**

✅ **Proteção automática** - Middleware aplicado em todas as rotas protegidas
✅ **Experiência fluida** - Redirecionamento inteligente após assinatura
✅ **Feedback claro** - Mensagens explicativas para o usuário
✅ **Menu integrado** - Acesso fácil à página de assinatura
✅ **Flexibilidade** - Fácil adicionar/remover proteção de rotas

---

*Última atualização: 18/07/2025*
*Versão do documento: 4.0* 