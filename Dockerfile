# ============================================================
# SAPTA HR System — Dockerfile kwa Render
# ============================================================
FROM php:8.3-cli-alpine

# ============================================================
# 1. Install system dependencies
# ============================================================
RUN apk add --no-cache \
    git \
    curl \
    unzip \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    postgresql-client \
    supervisor \
    nodejs \
    npm

# ============================================================
# 2. Install PHP extensions
# ============================================================
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# ============================================================
# 3. Install Composer
# ============================================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================================
# 4. Set working directory
# ============================================================
WORKDIR /var/www/html

# ============================================================
# 5. Copy project files
# ============================================================
COPY . .

# ============================================================
# 6. Install Composer dependencies
# ============================================================
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# ============================================================
# 7. Build frontend assets
# ============================================================
RUN npm install && npm run build || true

# ============================================================
# 8. Set permissions
# ============================================================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ============================================================
# 9. Expose port
# ============================================================
EXPOSE 10000

# ============================================================
# 10. Start command — AUTO MIGRATE
# ============================================================
CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=10000