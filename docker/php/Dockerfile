FROM php:8.4-fpm-alpine

# Diretório de trabalho
WORKDIR /var/www/html

# Instalação de dependências do sistema
RUN apk --no-cache add \
    autoconf \
    build-base \
    freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    libxpm-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgi \
    libmcrypt-dev \
    oniguruma-dev \
    libmemcached-dev \ 
    zlib

# Limpando cache
RUN rm -rf /var/cache/apk/*

# Instalando o Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Instalação de extensões do PHP
RUN pecl install -f memcached \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip exif pcntl bcmath opcache \
    && docker-php-ext-enable memcached

    # Ajustando permissões do diretório de trabalho
RUN find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \;

# Expondo porta 9000 do fpm
EXPOSE 9000

CMD ["php-fpm"]