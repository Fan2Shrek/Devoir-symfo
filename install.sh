docker compose up -d
docker compose exec php composer install
docker compose exec php bin/console d:d:c --if-not-exists
docker compose exec php bin/console d:m:m -n
docker compose exec php bin/console d:f:l -n
