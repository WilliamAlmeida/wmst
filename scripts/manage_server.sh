#!/bin/bash

# Função para mostrar o uso do script
# Garante execução a partir da raiz do projeto
cd "$(dirname "$0")/.." || exit 1

# Função para mostrar o uso do script
usage() {
    echo "Uso: $0 {start|stop} [porta]"
    echo "Uso: $0 {start-caddy|stop-caddy} [porta]"
    echo "Uso: $0 {start-queue|stop-queue}"
    echo "Uso: $0 {start-schedule|stop-schedule}"
    echo "Uso: $0 {start-horizon|stop-horizon}"
    exit 1
}

# Verifica se o script está sendo executado como root
if [ "$EUID" -ne 0 ]; then
    echo "Por favor, execute como root ou use sudo."
    exit 1
fi

# Verifica se o PHP está instalado
if ! command -v php &> /dev/null; then
    echo "PHP não está instalado. Por favor, instale o PHP antes de continuar."
    exit 1
fi

# Verifica se o Composer está instalado
if ! command -v composer &> /dev/null; then
    echo "Composer não está instalado. Por favor, instale o Composer antes de continuar."
    exit 1
fi

# Verifica se o Node.js está instalado
if ! command -v node &> /dev/null; then
    echo "Node.js não está instalado. Por favor, instale o Node.js antes de continuar."
    exit 1
fi

# Verifica se o FrankenPHP está instalado
check_frankenphp() {
    if ! command -v frankenphp &> /dev/null; then
        echo "FrankenPHP não está instalado. Por favor, instale o FrankenPHP antes de usar o modo Caddy."
        return 1
    fi
    return 0
}

# Verifica se o PM2 está instalado
if ! command -v pm2 &> /dev/null; then
    echo "PM2 não está instalado. Instalando PM2..."
    npm install -g pm2
fi

# Verifica se o número correto de argumentos foi passado
if [ $# -lt 1 ]; then
    usage
fi

# Define a porta padrão
PORT=9001

# Função para iniciar o servidor
start_server() {
    # Se o segundo argumento for passado, define a porta para o valor passado
    if [ ! -z "$2" ]; then
        PORT=$2
        # Valida se a porta é um número válido
        if ! [[ $PORT =~ ^[0-9]+$ ]] || [ "$PORT" -lt 1 ] || [ "$PORT" -gt 65535 ]; then
            echo "Erro: Porta inválida. Por favor, informe um número entre 1 e 65535."
            exit 1
        fi
    fi

    echo "Iniciando o servidor Laravel (nome: wmst-server) na porta $PORT..."
    # Add the -v flag to make artisan serve more verbose
    pm2 start "php artisan serve --host=0.0.0.0 --port=$PORT -v" --name wmst-server
}

# Função para parar o servidor
stop_server() {
    echo "Parando o servidor Laravel (nome: wmst-server)..."
    pm2 stop wmst-server &> /dev/null
    pm2 delete wmst-server &> /dev/null
}

# Função para iniciar queue worker
start_queue_worker() {
    echo "Iniciando worker da fila (nome: wmst-queue)..."
    pm2 start "php artisan queue:listen --queue=default,campaign --tries=5 --sleep=1 --timeout=60" --name wmst-queue
}

# Função para parar queue worker
stop_queue_worker() {
    echo "Parando worker da fila (nome: wmst-queue)..."
    if pm2 list | grep -q "wmst-queue"; then
        pm2 stop wmst-queue &> /dev/null
        pm2 delete wmst-queue &> /dev/null
        echo "Worker da fila parado com sucesso."
    else
        echo "Worker da fila não está em execução."
    fi
}

# Função para iniciar schedule worker
start_schedule_worker() {
    echo "Iniciando worker do scheduler (nome: wmst-schedule)..."
    pm2 start "php artisan schedule:work" --name wmst-schedule
}

# Função para parar schedule worker
stop_schedule_worker() {
    echo "Parando worker do scheduler (nome: wmst-schedule)..."
    if pm2 list | grep -q "wmst-schedule"; then
        pm2 stop wmst-schedule &> /dev/null
        pm2 delete wmst-schedule &> /dev/null
        echo "Worker do scheduler parado com sucesso."
    else
        echo "Worker do scheduler não está em execução."
    fi
}

# Função para iniciar Horizon
start_horizon() {
    echo "Iniciando o Laravel Horizon (nome: wmst-horizon)..."
    pm2 start "php artisan horizon" --name wmst-horizon
}

# Função para parar Horizon
stop_horizon() {
    echo "Parando o Laravel Horizon (nome: wmst-horizon)..."
    if pm2 list | grep -q "wmst-horizon"; then
        pm2 stop wmst-horizon &> /dev/null
        pm2 delete wmst-horizon &> /dev/null
        echo "Laravel Horizon parado com sucesso."
    else
        echo "Laravel Horizon não está em execução."
    fi
}

# Função para iniciar Caddy com FrankenPHP
start_caddy() {
    # Verifica se o FrankenPHP está instalado
    if ! check_frankenphp; then
        return 1
    fi
    
    # Se o segundo argumento for passado, define a porta para o valor passado
    CADDY_PORT=8001
    if [ ! -z "$2" ]; then
        CADDY_PORT=$2
        # Valida se a porta é um número válido
        if ! [[ $CADDY_PORT =~ ^[0-9]+$ ]] || [ "$CADDY_PORT" -lt 1 ] || [ "$CADDY_PORT" -gt 65535 ]; then
            echo "Erro: Porta inválida. Por favor, informe um número entre 1 e 65535."
            return 1
        fi
        
        # Atualiza a porta no Caddyfile temporário
        TEMP_CADDYFILE=$(mktemp)
        sed "s/:8001/:$CADDY_PORT/g" Caddyfile > "$TEMP_CADDYFILE"
        
        echo "Iniciando o servidor FrankenPHP com Caddy (nome: wmst-caddy) na porta $CADDY_PORT..."
        pm2 start "frankenphp run --config $TEMP_CADDYFILE" --name wmst-caddy
        
        # Aguarde um momento para garantir que o PM2 começou a usar o arquivo
        sleep 2
        # Remove o arquivo temporário
        rm "$TEMP_CADDYFILE"
    else
        echo "Iniciando o servidor FrankenPHP com Caddy (nome: wmst-caddy) na porta $CADDY_PORT..."
        pm2 start "frankenphp run" --name wmst-caddy
    fi
}

# Função para parar Caddy
stop_caddy() {
    echo "Parando o servidor FrankenPHP com Caddy (nome: wmst-caddy)..."
    if pm2 list | grep -q "wmst-caddy"; then
        pm2 stop wmst-caddy &> /dev/null
        pm2 delete wmst-caddy &> /dev/null
        echo "Servidor FrankenPHP com Caddy parado com sucesso."
    else
        echo "Servidor FrankenPHP com Caddy não está em execução."
    fi
}

# Verifica a ação (start ou stop)
case "$1" in
    start)
        start_server "$@"
        ;;
    stop)
        stop_server
        ;;
    start-caddy)
        start_caddy "$@"
        ;;
    stop-caddy)
        stop_caddy
        ;;
    start-queue)
        start_queue_worker
        ;;
    stop-queue)
        stop_queue_worker
        ;;
    start-schedule)
        start_schedule_worker
        ;;
    stop-schedule)
        stop_schedule_worker
        ;;
    start-horizon)
        start_horizon
        ;;
    stop-horizon)
        stop_horizon
        ;;
    *)
        usage
        ;;
esac
