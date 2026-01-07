# ---------- PHP + Composer ----------
FROM php:8.4-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# ---------- Frontend build (Vite) ----------
# install Node (simple method)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm ci \
    && npm run build

# ---------- Laravel deps ----------
RUN composer install --no-dev --optimize-autoloader

# permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

CMD ["sh", "-lc", "php -S 0.0.0.0:8080 -t public]
