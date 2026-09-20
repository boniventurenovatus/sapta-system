FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    git curl unzip libpng-dev libxml2-dev oniguruma-dev \
    libzip-dev postgresql-dev postgresql-client nodejs npm

RUN docker-php-ext-install \
    pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress
RUN npm install && npm run build || true

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

EXPOSE 10000

CMD php artisan migrate --force 2>&1 || echo "Migrate failed" ; \
    echo "=== TABLES ZILIZOPO ===" ; \
    php artisan tinker --execute="foreach(\DB::select(\"SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name\") as \$t) { echo \$t->table_name . PHP_EOL; }" 2>&1 ; \
    echo "=== MWISHO WA TABLES ===" ; \
    php artisan db:seed --class=SuperAdminSeeder --force 2>&1 || echo "Seed failed" ; \
    php artisan serve --host=0.0.0.0 --port=10000