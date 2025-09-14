#!/bin/bash

# Função para parar o servidor Laravel se estiver rodando
stop_server_if_running() {
    if pm2 list | grep -q "wmst-server"; then
        echo "Parando o servidor Laravel..."
        pm2 stop wmst-server
        pm2 delete wmst-server
    else
        echo "Servidor Laravel não está rodando."
    fi
}

# Para o servidor Laravel se estiver rodando
stop_server_if_running

# Remove dependências do Laravel
echo "Removendo dependências do Laravel..."
rm -rf vendor
rm -rf node_modules

# Remove Composer
echo "Removendo Composer..."
sudo rm /usr/local/bin/composer

# Remove PHP
echo "Removendo PHP..."
sudo apt remove --purge -y php php-cli php-mbstring php-xml php-curl php-sqlite3
sudo add-apt-repository --remove ppa:ondrej/php -y
sudo apt update

# Remove Node.js
echo "Removendo Node.js..."
sudo apt remove --purge -y nodejs
sudo rm -rf /etc/apt/sources.list.d/nodesource.list
sudo apt update

# Remove PM2
echo "Removendo PM2..."
sudo npm uninstall -g pm2

# Limpa pacotes não utilizados
echo "Limpando pacotes não utilizados..."
sudo apt autoremove -y
sudo apt clean

echo "Desinstalação do Ambiente Laravel concluída!"