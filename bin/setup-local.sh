cp .env.local .env
docker compose build
docker compose run --rm app composer install
docker compose run --rm app php artisan storage:link
docker compose up -d
sleep 3
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
