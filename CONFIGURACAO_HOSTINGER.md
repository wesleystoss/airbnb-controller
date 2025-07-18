# 🌐 Configuração de Cron Jobs na Hostinger - Sistema de Assinaturas

## 📋 Índice
1. [Visão Geral](#visão-geral)
2. [Pré-requisitos](#pré-requisitos)
3. [Configuração via SSH](#configuração-via-ssh)
4. [Configuração via Painel de Controle](#configuração-via-painel-de-controle)
5. [Verificação e Testes](#verificação-e-testes)
6. [Troubleshooting](#troubleshooting)
7. [Monitoramento](#monitoramento)

---

## 🎯 Visão Geral

Este documento explica como configurar os cron jobs necessários para o sistema de assinaturas do Airbnb Controle na Hostinger. Os cron jobs são essenciais para:

- ✅ **Verificar assinaturas expiradas** automaticamente
- 🔄 **Processar tentativas de cobrança** para pagamentos com falha
- 📊 **Gerar relatórios** de monitoramento
- 🚨 **Notificar sobre problemas** no sistema

---

## 📋 Pré-requisitos

### **Antes de começar, verifique:**

1. **Acesso SSH** habilitado na sua conta Hostinger
2. **Projeto Laravel** já deployado no servidor
3. **Comandos Artisan** funcionando localmente
4. **Permissões** adequadas no diretório do projeto

### **Informações necessárias:**
- ✅ Usuário SSH da Hostinger
- ✅ Senha ou chave SSH
- ✅ Caminho completo do projeto no servidor
- ✅ Versão do PHP disponível

---

## 🔧 Configuração via SSH (Recomendado)

### **Passo 1: Conectar via SSH**

```bash
# Conectar ao servidor
ssh usuario@seu-dominio.com
# ou
ssh usuario@IP-DO-SERVIDOR

# Exemplo:
ssh wesley@seudominio.com
```

### **Passo 2: Navegar até o projeto**

```bash
# Navegar para o diretório do projeto
cd /home/usuario/public_html/airbnb
# ou
cd /home/usuario/public_html/seu-projeto

# Verificar se está no diretório correto
ls -la
# Deve mostrar: artisan, composer.json, etc.
```

### **Passo 3: Verificar o PHP**

```bash
# Verificar se o PHP está disponível
which php

# Verificar a versão
php --version

# Se não funcionar, tente:
/usr/bin/php --version
# ou
/opt/alt/php81/usr/bin/php --version
```

### **Passo 4: Testar comandos Artisan**

```bash
# Testar se os comandos funcionam
php artisan list | grep assinatura

# Deve mostrar:
# assinatura:criar-gratuita
# assinaturas:processar-tentativas-cobranca
# assinaturas:verificar-expiradas
```

### **Passo 5: Configurar Cron Jobs**

#### **Opção A: Usando crontab -e (Editor)**

```bash
# Abrir editor de cron
crontab -e

# Adicionar estas linhas:
0 2 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
0 8 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1

# Salvar e sair (Ctrl+X, Y, Enter)
```

#### **Opção B: Usando echo (Mais rápido)**

```bash
# Adicionar cron job 1
echo "0 2 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1" | crontab -

# Adicionar cron job 2
(crontab -l 2>/dev/null; echo "0 8 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1") | crontab -
```

### **Passo 6: Verificar configuração**

```bash
# Listar cron jobs ativos
crontab -l

# Deve mostrar:
# 0 2 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
# 0 8 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1
```

---

## 🖥️ Configuração via Painel de Controle

### **Se não tiver acesso SSH:**

1. **Acesse o Painel de Controle da Hostinger**
   - Faça login na sua conta
   - Vá para o painel do seu domínio

2. **Encontre a Seção Cron Jobs**
   - Procure por **"Cron Jobs"** ou **"Agendador de Tarefas"**
   - Geralmente em **"Ferramentas Avançadas"**

3. **Configure os Cron Jobs**

#### **Cron Job 1: Verificar Assinaturas Expiradas**
```
Comando: cd /home/usuario/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
Frequência: Diário às 02:00
```

#### **Cron Job 2: Processar Tentativas de Cobrança**
```
Comando: cd /home/usuario/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1
Frequência: Diário às 08:00
```

---

## ✅ Verificação e Testes

### **Teste 1: Comandos Manuais**

```bash
# Testar verificação de assinaturas expiradas
php artisan assinaturas:verificar-expiradas

# Saída esperada:
# 🔍 Verificando assinaturas...
# 📅 Verificando assinaturas expiradas...
# ✅ Nenhuma assinatura expirada encontrada.
# 🔄 Verificando assinaturas recorrentes para renovação...
# ✅ Nenhuma assinatura recorrente para renovar.
# ✅ Processo concluído!

# Testar processamento de tentativas
php artisan assinaturas:processar-tentativas-cobranca

# Saída esperada:
# 🔄 Processando tentativas de cobrança...
# ✅ Nenhuma assinatura precisa de nova tentativa de cobrança.
```

### **Teste 2: Verificar Logs**

```bash
# Verificar logs do Laravel
tail -f storage/logs/laravel.log

# Verificar se há erros relacionados aos comandos
grep "assinatura" storage/logs/laravel.log
```

### **Teste 3: Verificar Cron Jobs**

```bash
# Listar cron jobs ativos
crontab -l

# Verificar logs do sistema cron (se disponível)
tail -f /var/log/cron.log
```

---

## 🚨 Troubleshooting

### **Problema 1: Comando PHP não encontrado**

```bash
# Solução: Usar caminho completo
which php
# Exemplo: /usr/bin/php

# Atualizar cron jobs com caminho completo
crontab -e
# Substituir "php" por "/usr/bin/php"
```

### **Problema 2: Permissões negadas**

```bash
# Verificar permissões do diretório
ls -la /home/usuario/public_html/airbnb

# Dar permissões adequadas
chmod 755 /home/usuario/public_html/airbnb
chmod +x /home/usuario/public_html/airbnb/artisan
```

### **Problema 3: Cron job não executa**

```bash
# Adicionar logs para debug
crontab -e

# Substituir por:
0 2 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /home/usuario/public_html/airbnb/storage/logs/cron.log 2>&1
0 8 * * * cd /home/usuario/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /home/usuario/public_html/airbnb/storage/logs/cron.log 2>&1

# Verificar logs
tail -f storage/logs/cron.log
```

### **Problema 4: Erro de ambiente**

```bash
# Verificar variáveis de ambiente
php artisan env

# Se necessário, especificar ambiente
php artisan assinaturas:verificar-expiradas --env=production
```

---

## 📊 Monitoramento

### **Script de Monitoramento**

```bash
# Executar monitoramento manual
./scripts/monitor_assinaturas.sh

# Agendar monitoramento (opcional)
echo "0 12 * * * cd /home/usuario/public_html/airbnb && ./scripts/monitor_assinaturas.sh >> /home/usuario/public_html/airbnb/storage/logs/monitor.log 2>&1" | crontab -
```

### **Verificações Diárias**

```bash
# 1. Verificar se cron jobs estão ativos
crontab -l

# 2. Verificar logs de execução
tail -20 storage/logs/laravel.log

# 3. Testar comandos manualmente
php artisan assinaturas:verificar-expiradas
php artisan assinaturas:processar-tentativas-cobranca

# 4. Verificar assinaturas no banco
php artisan tinker
>>> App\Models\Assinatura::where('status', 'ativa')->count();
```

---

## 📋 Checklist de Configuração

### **Antes da Configuração:**
- [ ] Acesso SSH habilitado
- [ ] Projeto Laravel deployado
- [ ] Comandos Artisan funcionando
- [ ] Permissões adequadas

### **Durante a Configuração:**
- [ ] Conectado via SSH
- [ ] Navegado para o diretório correto
- [ ] PHP verificado e funcionando
- [ ] Comandos testados manualmente
- [ ] Cron jobs configurados
- [ ] Configuração verificada

### **Após a Configuração:**
- [ ] Testes manuais executados
- [ ] Logs verificados
- [ ] Monitoramento configurado
- [ ] Documentação atualizada

---

## 🎯 Exemplo Completo

### **Sessão SSH Completa:**

```bash
# 1. Conectar
ssh wesley@seudominio.com

# 2. Navegar
cd /home/wesley/public_html/airbnb

# 3. Verificar
which php
php --version
php artisan list | grep assinatura

# 4. Configurar cron jobs
crontab -e

# 5. Adicionar linhas:
0 2 * * * cd /home/wesley/public_html/airbnb && php artisan assinaturas:verificar-expiradas >> /dev/null 2>&1
0 8 * * * cd /home/wesley/public_html/airbnb && php artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1

# 6. Salvar (Ctrl+X, Y, Enter)

# 7. Verificar
crontab -l

# 8. Testar
php artisan assinaturas:verificar-expiradas
php artisan assinaturas:processar-tentativas-cobranca

# 9. Sair
exit
```

---

## 📞 Suporte

### **Em caso de problemas:**

1. **Verifique os logs**: `tail -f storage/logs/laravel.log`
2. **Teste comandos manualmente**: `php artisan assinaturas:verificar-expiradas`
3. **Verifique cron jobs**: `crontab -l`
4. **Consulte a documentação**: `GERENCIAMENTO_ASSINATURAS.md`
5. **Execute monitoramento**: `./scripts/monitor_assinaturas.sh`

### **Contatos:**
- 📧 **Email**: wesleyrogerio77@gmail.com
- 📋 **Documentação**: `GERENCIAMENTO_ASSINATURAS.md`
- 🐛 **Issues**: Repositório do projeto

---

*Última atualização: 18/07/2025*
*Versão do documento: 1.0* 