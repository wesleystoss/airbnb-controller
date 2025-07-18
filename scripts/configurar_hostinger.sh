#!/bin/bash

# 🌐 Script de Configuração de Cron Jobs na Hostinger
# Sistema de Assinaturas - Airbnb Controle

echo "🌐 Configuração de Cron Jobs na Hostinger"
echo "=========================================="
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir com cores
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    print_error "Arquivo 'artisan' não encontrado!"
    print_info "Certifique-se de estar no diretório raiz do projeto Laravel"
    exit 1
fi

print_status "Diretório do projeto verificado"

# Verificar PHP
if ! command -v php &> /dev/null; then
    print_error "PHP não encontrado no PATH"
    print_info "Tentando caminhos alternativos..."
    
    # Tentar caminhos comuns da Hostinger
    PHP_PATHS=("/usr/bin/php" "/opt/alt/php81/usr/bin/php" "/opt/alt/php82/usr/bin/php" "/opt/alt/php83/usr/bin/php")
    
    for path in "${PHP_PATHS[@]}"; do
        if [ -f "$path" ]; then
            print_status "PHP encontrado em: $path"
            PHP_CMD="$path"
            break
        fi
    done
    
    if [ -z "$PHP_CMD" ]; then
        print_error "PHP não encontrado. Configure manualmente."
        exit 1
    fi
else
    PHP_CMD="php"
    print_status "PHP encontrado: $(which php)"
fi

# Verificar versão do PHP
PHP_VERSION=$($PHP_CMD --version | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
print_info "Versão do PHP: $PHP_VERSION"

# Testar comandos Artisan
print_info "Testando comandos Artisan..."

if ! $PHP_CMD artisan list | grep -q "assinatura"; then
    print_error "Comandos de assinatura não encontrados!"
    print_info "Verifique se o projeto está configurado corretamente"
    exit 1
fi

print_status "Comandos Artisan funcionando"

# Obter caminho atual
CURRENT_PATH=$(pwd)
print_info "Diretório atual: $CURRENT_PATH"

# Verificar se já existem cron jobs
EXISTING_CRONS=$(crontab -l 2>/dev/null | grep -c "assinatura" || echo "0")

if [ "$EXISTING_CRONS" -gt 0 ]; then
    print_warning "Cron jobs de assinatura já existem!"
    echo ""
    echo "Cron jobs atuais:"
    crontab -l | grep "assinatura" || echo "Nenhum encontrado"
    echo ""
    
    read -p "Deseja substituir os cron jobs existentes? (y/N): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_info "Configuração cancelada"
        exit 0
    fi
    
    # Remover cron jobs existentes
    crontab -l 2>/dev/null | grep -v "assinatura" | crontab -
    print_status "Cron jobs antigos removidos"
fi

# Criar cron jobs
print_info "Configurando cron jobs..."

# Cron job 1: Verificar assinaturas expiradas (02:00)
CRON_1="0 2 * * * cd $CURRENT_PATH && $PHP_CMD artisan assinaturas:verificar-expiradas >> /dev/null 2>&1"

# Cron job 2: Processar tentativas de cobrança (08:00)
CRON_2="0 8 * * * cd $CURRENT_PATH && $PHP_CMD artisan assinaturas:processar-tentativas-cobranca >> /dev/null 2>&1"

# Adicionar cron jobs
(crontab -l 2>/dev/null; echo "$CRON_1"; echo "$CRON_2") | crontab -

if [ $? -eq 0 ]; then
    print_status "Cron jobs configurados com sucesso!"
else
    print_error "Erro ao configurar cron jobs"
    exit 1
fi

# Verificar configuração
echo ""
print_info "Cron jobs configurados:"
crontab -l | grep "assinatura"

# Testar comandos
echo ""
print_info "Testando comandos..."

echo "Testando verificação de assinaturas expiradas..."
if $PHP_CMD artisan assinaturas:verificar-expiradas > /dev/null 2>&1; then
    print_status "Comando de verificação funcionando"
else
    print_warning "Comando de verificação retornou erro (pode ser normal se não há assinaturas)"
fi

echo "Testando processamento de tentativas..."
if $PHP_CMD artisan assinaturas:processar-tentativas-cobranca > /dev/null 2>&1; then
    print_status "Comando de processamento funcionando"
else
    print_warning "Comando de processamento retornou erro (pode ser normal se não há tentativas)"
fi

# Criar arquivo de log para cron jobs (opcional)
CRON_LOG="$CURRENT_PATH/storage/logs/cron.log"
mkdir -p "$(dirname "$CRON_LOG")"
touch "$CRON_LOG"

print_info "Log de cron jobs criado: $CRON_LOG"

# Configurar monitoramento (opcional)
read -p "Deseja configurar monitoramento diário? (y/N): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    MONITOR_CRON="0 12 * * * cd $CURRENT_PATH && ./scripts/monitor_assinaturas.sh >> $CURRENT_PATH/storage/logs/monitor.log 2>&1"
    (crontab -l 2>/dev/null; echo "$MONITOR_CRON") | crontab -
    print_status "Monitoramento configurado para 12:00 diariamente"
fi

echo ""
echo "=========================================="
print_status "Configuração concluída com sucesso!"
echo ""
echo "📋 Resumo da configuração:"
echo "• Cron job 1: Verificar assinaturas expiradas (02:00)"
echo "• Cron job 2: Processar tentativas de cobrança (08:00)"
echo "• Log de cron jobs: $CRON_LOG"
echo ""
echo "🔍 Para verificar:"
echo "• Listar cron jobs: crontab -l"
echo "• Ver logs: tail -f $CRON_LOG"
echo "• Testar manualmente: $PHP_CMD artisan assinaturas:verificar-expiradas"
echo ""
echo "📚 Documentação: CONFIGURACAO_HOSTINGER.md"
echo "" 