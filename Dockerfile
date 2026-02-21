FROM php:8.4-cli

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

WORKDIR /app
COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Node + build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs
RUN npm install && npm run build

EXPOSE 8000

CMD npm run build && \
    php artisan migrate --force && \
    php artisan config:cache && php artisan route:cache && php artisan view:cache && \
    php -S 0.0.0.0:${PORT:-8000} -t public public/index.php

