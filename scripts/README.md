# 🛠️ Scripts de Monitoramento - Airbnb Controle

Este diretório contém scripts úteis para monitoramento e gerenciamento do sistema de assinaturas.

## 📋 Scripts Disponíveis

### 1. `monitor_assinaturas.sh`
Script completo de monitoramento que verifica:
- ✅ Assinaturas expiradas
- 📊 Relatório de assinaturas por status
- 🔗 Webhooks recebidos hoje
- 🚨 Erros nos logs
- 🌐 Status do webhook
- 💾 Uso de disco
- 📄 Tamanho do arquivo de log

**Uso:**
```bash
./scripts/monitor_assinaturas.sh
```

### 2. `configurar_hostinger.sh`
Script automatizado para configurar cron jobs na Hostinger:
- 🔧 Configura cron jobs automaticamente
- ✅ Verifica PHP e comandos Artisan
- 🛠️ Detecta caminhos do PHP na Hostinger
- 📝 Cria logs de cron jobs
- 🔍 Testa comandos após configuração
- 📊 Configura monitoramento opcional

**Uso:**
```bash
./scripts/configurar_hostinger.sh
```

**Exemplo de uso na Hostinger:**
```bash
# 1. Conectar via SSH
ssh usuario@seudominio.com

# 2. Navegar para o projeto
cd /home/usuario/public_html/airbnb

# 3. Executar script
./scripts/configurar_hostinger.sh
```

**Saída esperada:**
```
🔍 Monitoramento de Assinaturas - Airbnb Controle
📅 Data: 2025-07-18
==================================

📅 Verificando assinaturas expiradas...
✅ Nenhuma assinatura expirada encontrada.

📊 Gerando relatório de assinaturas...
📈 Relatório de Assinaturas:
   • Ativas: 1
   • Canceladas: 0
   • Expiradas: 0
   • Pendentes: 0
   • Expirando em 7 dias: 0
   • Expirando em 30 dias: 1

🔗 Verificando webhooks recebidos hoje...
✅ Webhooks recebidos hoje: 3

🚨 Verificando erros nos logs...
✅ Nenhum erro encontrado hoje

🌐 Verificando status do webhook...
✅ Webhook respondendo corretamente (Status: 200)

💾 Verificando espaço em disco...
✅ Uso de disco normal: 45%

📄 Verificando tamanho do arquivo de log...
📊 Tamanho do log: 2.1M

==================================
📋 Resumo do Monitoramento
✅ Monitoramento concluído com sucesso!
📅 Próxima verificação recomendada: 2025-07-19
📞 Para suporte, consulte: GERENCIAMENTO_ASSINATURAS.md

💾 Salvando relatório em: /home/wesley/Documentos/Projetos/airbnb/storage/logs/relatorio_assinaturas_2025-07-18.log
✅ Relatório salvo com sucesso!
```

## 🔧 Configuração

### 1. Tornar Scripts Executáveis
```bash
chmod +x scripts/*.sh
```

### 2. Configurar Cron Job para Monitoramento Automático
```bash
# Editar crontab
crontab -e

# Adicionar linha para executar monitoramento diariamente às 9h
0 9 * * * /home/wesley/Documentos/Projetos/airbnb/scripts/monitor_assinaturas.sh >> /home/wesley/Documentos/Projetos/airbnb/storage/logs/monitoramento.log 2>&1
```

### 3. Configurar Alerta por Email (Opcional)
```bash
# Adicionar ao final do script monitor_assinaturas.sh
if [ $ERROR_COUNT -gt 0 ]; then
    echo "Alerta: $ERROR_COUNT erros encontrados no sistema de assinaturas" | mail -s "Alerta Airbnb Controle" seu-email@exemplo.com
fi
```

## 📊 Relatórios Gerados

### Relatório Diário
O script gera um relatório diário em:
```
storage/logs/relatorio_assinaturas_YYYY-MM-DD.log
```

**Conteúdo do relatório:**
- Data e hora da verificação
- Número de webhooks recebidos
- Número de erros encontrados
- Status do webhook
- Uso de disco
- Tamanho do arquivo de log

### Log de Monitoramento
Se configurado via cron, o log de execução fica em:
```
storage/logs/monitoramento.log
```

## 🚨 Alertas e Notificações

### Condições de Alerta
- ❌ Mais de 5 erros por dia
- ⚠️ Uso de disco acima de 80%
- 🔴 Webhook não respondendo (status != 200)
- 📅 Assinaturas expirando em 7 dias

### Configurar Notificações
```bash
# Exemplo: Enviar notificação para Slack
curl -X POST -H 'Content-type: application/json' \
  --data '{"text":"Alerta: Sistema de assinaturas com problemas"}' \
  https://hooks.slack.com/services/YOUR_WEBHOOK_URL
```

## 🔍 Troubleshooting

### Problemas Comuns

#### 1. Script não executa
```bash
# Verificar permissões
ls -la scripts/monitor_assinaturas.sh

# Verificar se o caminho está correto
head -5 scripts/monitor_assinaturas.sh
```

#### 2. Erro de permissão
```bash
# Corrigir permissões
chmod +x scripts/monitor_assinaturas.sh
```

#### 3. Caminho incorreto do projeto
```bash
# Editar o script e corrigir PROJECT_PATH
nano scripts/monitor_assinaturas.sh
```

#### 4. Log muito grande
```bash
# Limpar logs antigos
find storage/logs -name "*.log" -mtime +30 -delete
```

## 📝 Personalização

### Adicionar Novas Verificações
```bash
# Editar o script
nano scripts/monitor_assinaturas.sh

# Adicionar nova verificação
echo -e "\n${YELLOW}🔧 Nova verificação...${NC}"
# Seu código aqui
```

### Modificar Cores
```bash
# Cores disponíveis no script
RED='\033[0;31m'      # Vermelho
GREEN='\033[0;32m'    # Verde
YELLOW='\033[1;33m'   # Amarelo
BLUE='\033[0;34m'     # Azul
NC='\033[0m'          # Sem cor
```

## 📞 Suporte

Para dúvidas sobre os scripts:
1. Consulte `GERENCIAMENTO_ASSINATURAS.md`
2. Verifique os logs em `storage/logs/`
3. Execute o script manualmente para debug

---

*Última atualização: 18/07/2025*
*Versão: 1.0* 