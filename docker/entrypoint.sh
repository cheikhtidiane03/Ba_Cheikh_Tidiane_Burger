#!/bin/sh
set -e

echo "🍔 ISI BURGER — Démarrage..."

# Attendre PostgreSQL avec une méthode qui fonctionne
echo "⏳ Attente de la base de données..."
MAX_TRIES=30
COUNT=0
until php -r "
    try {
        \$pdo = new PDO(
            'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        echo 'OK';
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null | grep -q "OK"; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "❌ Base de données non disponible après $MAX_TRIES tentatives"
        exit 1
    fi
    echo "   Tentative $COUNT/$MAX_TRIES..."
    sleep 2
done
echo "✅ Base de données prête !"

# Générer la clé si absente
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Génération de la clé..."
    php artisan key:generate --force
fi

# Migrations
echo "🗄️ Migrations..."
php artisan migrate --force --no-interaction

# Optimisation
echo "⚡ Optimisation..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "🚀 ISI BURGER prêt sur le port 80 !"

exec /usr/bin/supervisord -c /etc/supervisord.conf