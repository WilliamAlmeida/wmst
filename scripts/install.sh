#!/bin/bash

# Atualiza o sistema
echo "Atualizando o sistema..."
sudo apt update -y && sudo apt upgrade -y

# Instalação do PHP (última versão)
echo "Instalando PHP..."
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php php-cli php-mbstring php-xml php-curl php-sqlite3

# Verifica a instalação do PHP
echo "Verificando versão do PHP..."
php -v

# Instalação do Composer
echo "Instalando Composer..."
sudo apt install -y curl unzip
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verifica a instalação do Composer
echo "Verificando versão do Composer..."
composer -v

# Instalação do Node.js (última versão estável)
echo "Instalando Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verifica a instalação do Node.js
echo "Verificando versões do Node.js e NPM..."
node -v
npm -v

# Instalação do PM2
echo "Instalando PM2..."
sudo npm install -g pm2

# Instalação das dependências do Laravel
echo "Instalando dependências do Laravel..."
composer install
npm install

# Geração da chave da aplicação Laravel
echo "Gerando chave da aplicação Laravel..."
php artisan key:generate

# Roda migrações
echo "Rodando migrações..."
php artisan migrate

# Roda reinit
echo "Rodando reinit..."
php artisan reinit

echo "Instalação do Ambiente Laravel concluída!"