<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('FetchStockPrices', function () {
    $this->comment('Fetching stock prices...');
    $this->call('app:fetch-stock-prices');
})->purpose('Fetch stock prices from the API')->everyMinute();
