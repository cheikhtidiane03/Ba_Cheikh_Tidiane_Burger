# ══════════════════════════════════════════════════
#  ISI BURGER — Dockerfile corrigé
# ══════════════════════════════════════════════════

# ── Stage 1 : Build assets JS/CSS ─────────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./
COPY tailwind.config.js ./

# postcss.config.js est optionnel
COPY resources/css ./resources/css
COPY resources/js  ./resources/js

RUN npm ci
RUN npm run build

# ── Stage 2 : Application PHP ─────────────────────
FROM php:8.2-fpm-alpine

LABEL maintainer="ISI BURGER"

# Dépendances système
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

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip bcmath opcache pcntl

# Autoriser Composer en root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Copier composer files
COPY composer.json composer.lock ./

# 2. Installer dépendances PHP
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# 3. Copier tout le code
COPY . .

# 4. Copier les assets buildés
COPY --from=node-builder /app/public/build ./public/build

# 5. Générer l'autoloader APRÈS avoir copié le code
RUN composer dump-autoload --optimize --no-dev

# 6. Créer le fichier .env pour la production
RUN cp .env.example .env \
    && php artisan key:generate --force

# 7. Permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Configs serveur
COPY docker/nginx.conf      /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini         /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh   /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]