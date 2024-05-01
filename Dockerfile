# Utiliza la imagen oficial de PHP como base
FROM php:8.2

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql

# instalar y habilitar las extensiones de PHP necesarias gd y zip
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configura las variables de entorno para Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

# Agrega el directorio del vendor a la variable PATH
ENV PATH="${PATH}:/composer/vendor/bin"

# Establece el directorio de trabajo
WORKDIR /var/www/html

COPY . .

# Instala las dependencias de Composer
RUN composer install

# Expone el puerto 80 para acceder a la aplicación Laravel
EXPOSE 80

# Comando por defecto para iniciar la aplicación
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "80"]
