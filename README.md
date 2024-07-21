1. cp .env.example .env
2. composer install
3. Run This command:  ./vendor/bin/sail up -d 
4. Run Migrations: ./vendor/bin/sail artisan migrate
5. Run Seeder: ./vendor/bin/sail artisan db:seed
6. Run Command to fetch Stock Data: ./vendor/bin/sail artisan app:fetch-stock-prices
7. For Testing: ./vendor/bin/sail artisan migrate --env=testing   
