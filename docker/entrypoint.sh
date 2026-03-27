#!/bin/sh
# ══════════════════════════════════════
#  ISI BURGER — Entrypoint Docker
# ══════════════════════════════════════

set -e

echo "🍔 ISI BURGER — Démarrage..."

# Attendre que PostgreSQL soit prêt
echo "⏳ Attente de la base de données..."
until php artisan db:monitor --databases=pgsql 2>/dev/null; do
    sleep 2
done
echo "✅ Base de données prête !"

# Générer la clé si absente
if [ -z "$APP_KEY" ]; then
    echo "🔑 Génération de la clé applicative..."
    php artisan key:generate --force
fi

# Migrations
echo "🗄️  Lancement des migrations..."
php artisan migrate --force --no-interaction

# Cache de configuration
echo "⚡ Optimisation..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Lien symbolique storage
php artisan storage:link --force 2>/dev/null || true

echo "🚀 ISI BURGER prêt sur le port 80 !"

# Démarrer supervisord (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf