<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FetchStockPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-stock-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $response = Http::get("https://www.alphavantage.co/query", [
                'function' => 'TIME_SERIES_INTRADAY',
                'symbol' => $stock->symbol,
                'interval' => '1min',
                'apikey' => env('ALPHA_VANTAGE_API_KEY')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $latestData = array_values($data['Time Series (1min)'])[0];
                $stockPrice = StockPrice::create([
                    'stock_id' => $stock->id,
                    'price' => $latestData['4. close'],
                    'timestamp' => now(),
                ]);

                Cache::put("stock_{$stock->symbol}_latest", $stockPrice, now()->addMinutes(1));
            }
        }

        $this->info('Stock prices fetched and cached successfully.');
    }
}
