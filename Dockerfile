FROM php:8.4-cli

# System deps
CMD php artisan migrate --force && \
    php artisan config:cache && php artisan route:cache && php artisan view:cache && \
    php artisan serve --host 0.0.0.0 --port ${PORT:-8000}

WORKDIR /app
COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache


# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader


# Node + build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs
RUN npm install && npm run build

EXPOSE 8000
CMD php artisan serve --host 0.0.0.0 --port ${PORT:-8000}
