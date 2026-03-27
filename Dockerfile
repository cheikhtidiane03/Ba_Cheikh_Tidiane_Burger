# ══════════════════════════════════════════════════
#  ISI BURGER — Dockerfile
#  Multi-stage build : optimisé pour la production
# ══════════════════════════════════════════════════

# ── Stage 1 : Build des assets JS/CSS ─────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copier uniquement les fichiers nécessaires au build
COPY package*.json ./
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
COPY resources/css ./resources/css
COPY resources/js ./resources/js

# Installer les dépendances et builder
RUN npm ci --prefer-offline
RUN npm run build

# ── Stage 2 : Application PHP ─────────────────────
FROM php:8.2-fpm-alpine AS app

# Métadonnées
LABEL maintainer="ISI BURGER"
LABEL description="Application Laravel ISI BURGER"

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
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        bcmath \
        opcache \
        pcntl

ENV COMPOSER_ALLOW_SUPERUSER=1
# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Créer l'utilisateur applicatif (sécurité)
RUN addgroup -g 1000 -S laravel \
    && adduser -u 1000 -S laravel -G laravel

WORKDIR /var/www/html

# Copier les fichiers de dépendances d'abord (cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances PHP (sans dev en production)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copier le code source
COPY . .

# Récupérer les assets buildés depuis le stage node
COPY --from=node-builder /app/public/build ./public/build

# Finaliser l'autoloader
RUN composer install --no-dev --optimize-autoloader --no-scripts
# Permissions
RUN chown -R laravel:laravel /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copier les configs serveur
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Exposer le port
EXPOSE 80

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

# Script de démarrage
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

USER laravel

ENTRYPOINT ["/entrypoint.sh"]