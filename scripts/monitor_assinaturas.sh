#!/bin/bash

# Script de Monitoramento de Assinaturas - Airbnb Controle
# Uso: ./scripts/monitor_assinaturas.sh

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configurações
PROJECT_PATH="/home/wesley/Documentos/Projetos/airbnb"
LOG_FILE="$PROJECT_PATH/storage/logs/laravel.log"
DATE=$(date +%Y-%m-%d)

echo -e "${BLUE}🔍 Monitoramento de Assinaturas - Airbnb Controle${NC}"
echo -e "${BLUE}📅 Data: $DATE${NC}"
echo "=================================="

# Função para executar comando Laravel
run_artisan() {
    cd $PROJECT_PATH && php artisan $1
}

# 1. Verificar assinaturas expiradas
echo -e "\n${YELLOW}📅 Verificando assinaturas expiradas...${NC}"
run_artisan "assinaturas:verificar-expiradas"

# 2. Gerar relatório de assinaturas
echo -e "\n${YELLOW}📊 Gerando relatório de assinaturas...${NC}"
run_artisan "tinker --execute=\"echo '📈 Relatório de Assinaturas:' . PHP_EOL; echo '   • Ativas: ' . App\Models\Assinatura::where('status', 'ativa')->count() . PHP_EOL; echo '   • Canceladas: ' . App\Models\Assinatura::where('status', 'cancelada')->count() . PHP_EOL; echo '   • Expiradas: ' . App\Models\Assinatura::where('status', 'expirada')->count() . PHP_EOL; echo '   • Pendentes: ' . App\Models\Assinatura::where('status', 'pendente')->count() . PHP_EOL; echo '   • Expirando em 7 dias: ' . App\Models\Assinatura::where('status', 'ativa')->where('data_expiracao', '<=', now()->addDays(7))->count() . PHP_EOL; echo '   • Expirando em 30 dias: ' . App\Models\Assinatura::where('status', 'ativa')->where('data_expiracao', '<=', now()->addDays(30))->count() . PHP_EOL;\""

# 3. Verificar webhooks recebidos hoje
echo -e "\n${YELLOW}🔗 Verificando webhooks recebidos hoje...${NC}"
WEBHOOK_COUNT=$(grep "Webhook Mercado Pago recebido" $LOG_FILE | grep $DATE | wc -l)
echo -e "${GREEN}✅ Webhooks recebidos hoje: $WEBHOOK_COUNT${NC}"

# 4. Verificar erros nos logs
echo -e "\n${YELLOW}🚨 Verificando erros nos logs...${NC}"
ERROR_COUNT=$(grep "ERROR" $LOG_FILE | grep $DATE | wc -l)
if [ $ERROR_COUNT -gt 0 ]; then
    echo -e "${RED}❌ Encontrados $ERROR_COUNT erros hoje${NC}"
    echo -e "${YELLOW}Últimos 5 erros:${NC}"
    grep "ERROR" $LOG_FILE | grep $DATE | tail -5
else
    echo -e "${GREEN}✅ Nenhum erro encontrado hoje${NC}"
fi

# 5. Verificar status do webhook
echo -e "\n${YELLOW}🌐 Verificando status do webhook...${NC}"
WEBHOOK_URL="https://a5dfef01245f.ngrok-free.app/api/webhook/mercadopago"
TEST_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -X POST $WEBHOOK_URL \
  -H "Content-Type: application/json" \
  -d '{"test": "monitoring"}')

if [ "$TEST_RESPONSE" = "200" ]; then
    echo -e "${GREEN}✅ Webhook respondendo corretamente (Status: $TEST_RESPONSE)${NC}"
else
    echo -e "${RED}❌ Webhook com problema (Status: $TEST_RESPONSE)${NC}"
fi

# 6. Verificar espaço em disco
echo -e "\n${YELLOW}💾 Verificando espaço em disco...${NC}"
DISK_USAGE=$(df -h $PROJECT_PATH | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo -e "${RED}⚠️  Uso de disco alto: ${DISK_USAGE}%${NC}"
else
    echo -e "${GREEN}✅ Uso de disco normal: ${DISK_USAGE}%${NC}"
fi

# 7. Verificar tamanho do log
echo -e "\n${YELLOW}📄 Verificando tamanho do arquivo de log...${NC}"
LOG_SIZE=$(du -h $LOG_FILE | cut -f1)
echo -e "${BLUE}📊 Tamanho do log: $LOG_SIZE${NC}"

# 8. Resumo final
echo -e "\n${BLUE}=================================="
echo -e "📋 Resumo do Monitoramento${NC}"
echo -e "${GREEN}✅ Monitoramento concluído com sucesso!${NC}"
echo -e "${BLUE}📅 Próxima verificação recomendada: $(date -d '+1 day' +%Y-%m-%d)${NC}"
echo -e "${BLUE}📞 Para suporte, consulte: GERENCIAMENTO_ASSINATURAS.md${NC}"

# Salvar relatório em arquivo
REPORT_FILE="$PROJECT_PATH/storage/logs/relatorio_assinaturas_$DATE.log"
echo -e "\n${YELLOW}💾 Salvando relatório em: $REPORT_FILE${NC}"
{
    echo "Relatório de Assinaturas - $DATE"
    echo "=================================="
    echo "Webhooks recebidos hoje: $WEBHOOK_COUNT"
    echo "Erros encontrados: $ERROR_COUNT"
    echo "Status webhook: $TEST_RESPONSE"
    echo "Uso de disco: ${DISK_USAGE}%"
    echo "Tamanho do log: $LOG_SIZE"
} > $REPORT_FILE

echo -e "${GREEN}✅ Relatório salvo com sucesso!${NC}" 