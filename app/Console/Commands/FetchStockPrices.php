<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FetchStockPrices extends Command
{
    protected $signature = 'app:fetch-stock-prices';
    protected $description = 'Fetches stock prices and caches them.';
    private mixed $apiKey;
    private mixed $apiUrl;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('ALPHA_VANTAGE_API_KEY');
        $this->apiUrl = env('APIURL');
    }

    public function handle()
    {
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $data = $this->fetchStockData($stock->symbol);

            if ($data === null) continue;

            $this->cacheStockPrice($stock, $data);
        }

        Cache::forget('all_stock_reports');

        $this->info('Stock prices fetched successfully.');
    }

    private function fetchStockData($symbol)
    {
        try {
            $response = Http::get($this->apiUrl, [
                'function' => 'TIME_SERIES_INTRADAY',
                'symbol' => $symbol,
                'apikey' => $this->apiKey,
            ]);

            $data = json_decode($response->body(), true);

            if (isset($data['Time Series (INTRADAY)'])) {
                return $data['Time Series (INTRADAY)'];
            } else {
                $this->error("No 'Time Series (INTRADAY)' data for {$symbol}.");
                return null;
            }

        } catch (\Exception $e) {
            $this->error("Exception fetching data for {$symbol}: " . $e->getMessage());
            return null;
        }
    }

    private function cacheStockPrice($stock, $timeSeries): void
    {
        $latestDate = array_key_first($timeSeries);
        $latestData = $timeSeries[$latestDate];

        $existingStockPrices = StockPrice::where('stock_id', $stock->id)
            ->orderByDesc('created_at')
            ->take(2)
            ->get();

        if ($existingStockPrices->count() < 2) {
            StockPrice::create([
                'stock_id' => $stock->id,
                'price' => $latestData['4. close'],
            ]);
        } else {
            $oldestStockPrice = $existingStockPrices->last();
            $oldestStockPrice->update([
                'price' => $latestData['4. close'],
            ]);
        }
    }
}
