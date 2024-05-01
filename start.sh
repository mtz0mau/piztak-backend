#!/bin/bash

# Configura las variables de entorno necesarias
cp .env.example .env

# Instala las dependencias de Composer si no están instaladas
composer install

# Genera la clave de la aplicación si no existe
php artisan key:generate

# Inicia el servidor web de Laravel
php artisan serve --host 0.0.0.0 --port 8000
