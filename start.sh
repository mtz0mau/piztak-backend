#!/bin/bash

# Configura las variables de entorno necesarias
cp .env.example .env

# Actualiza el archivo .env con las variables de entorno proporcionadas por Docker
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env

# Instala las dependencias de Composer si no están instaladas
if [ ! -d "./vendor" ]; then
    composer install
fi

# Genera la clave de la aplicación si no existe
if [ ! -f "./storage/app/key.txt" ]; then
    php artisan key:generate --show >> ./storage/app/key.txt
fi

# Inicia el servidor web de Laravel
php artisan serve --host 0.0.0.0 --port 8000
