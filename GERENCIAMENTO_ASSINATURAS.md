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

*Última atualização: 18/07/2025*
*Versão do documento: 1.0* 