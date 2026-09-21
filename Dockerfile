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
RUN npm install
RUN npm run build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

EXPOSE 10000

CMD php artisan config:clear 2>&1 ; \
    php artisan route:clear 2>&1 ; \
    php artisan view:clear 2>&1 ; \
    php artisan cache:clear 2>&1 ; \
    php artisan migrate --force 2>&1 || echo "Migrate failed" ; \
    php artisan db:seed --class=FullAccessSeeder --force 2>&1 || echo "FullAccessSeeder failed" ; \
    php artisan db:seed --class=DatabaseImportSeeder --force 2>&1 || echo "DatabaseImportSeeder failed" ; \
    php artisan db:seed --class=OrganizationalUnitsAndPositionsSeeder --force 2>&1 || echo "OrganizationalUnitsAndPositionsSeeder failed" ; \
    php artisan config:cache 2>&1 ; \
    php artisan serve --host=0.0.0.0 --port=10000