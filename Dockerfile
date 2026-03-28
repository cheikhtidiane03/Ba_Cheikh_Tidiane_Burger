# ══════════════════════════════════════════════════
#  ISI BURGER — Dockerfile Final Corrigé
# ══════════════════════════════════════════════════

# ── Stage 1 : Build assets JS/CSS ─────────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY resources ./resources

RUN npm ci && npm run build

# ── Stage 2 : Application PHP ─────────────────────
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    nginx \
    supervisor

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip bcmath opcache pcntl

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier composer files en premier (cache Docker layers)
COPY composer.json composer.lock ./

# Installer dépendances PHP
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copier tout le code source
COPY . .

# Copier les assets buildés depuis le stage node
COPY --from=node-builder /app/public/build ./public/build

# Finaliser autoloader APRÈS avoir tout le code
RUN composer dump-autoload --optimize --no-dev

# Créer .env depuis example
RUN cp .env.example .env && php artisan key:generate --force

# Créer les dossiers storage nécessaires
RUN mkdir -p storage/logs \
             storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             bootstrap/cache

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Configs serveur
COPY docker/nginx.conf       /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini          /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh    /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]