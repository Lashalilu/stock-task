1. cp .env.example .env
2. composer install
3. ./vendor/bin/sail build --no-cache
4. Run This command:  ./vendor/bin/sail up -d
5. Run Migrations: ./vendor/bin/sail artisan migrate
6. Run Seeder: ./vendor/bin/sail artisan db:seed
7. Run Command to fetch Stock Data: ./vendor/bin/sail artisan app:fetch-stock-prices
8. For Testing: ./vendor/bin/sail artisan migrate --env=testing   
