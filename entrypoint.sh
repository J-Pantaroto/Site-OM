#!/bin/bash
set -e

echo "▶️ Inicializando container..."

if [ ! -f ".env" ]; then
  echo "📄 Copiando .env.example para .env"
  cp .env.example .env
fi

if ! grep -q '^APP_KEY=' .env || grep -q 'APP_KEY=$' .env; then
  echo "🔑 Gerando APP_KEY..."
  php artisan key:generate
fi

echo " Rodando comandos Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force || echo " Migrations falharam (provavelmente ja executadas)"

rm -rf public/storage
php artisan storage:link || echo " Storage link ja existe"

php artisan db:seed || echo " Seed falhou (possivelmente dados duplicados)"

exec php-fpm
