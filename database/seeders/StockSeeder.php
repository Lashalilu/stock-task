<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        $stocks = [
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.'],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.'],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation'],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc.'],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc.'],
            ['symbol' => 'FB', 'name' => 'Meta Platforms Inc.'],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA Corporation'],
            ['symbol' => 'NFLX', 'name' => 'Netflix Inc.'],
            ['symbol' => 'BABA', 'name' => 'Alibaba Group'],
            ['symbol' => 'V', 'name' => 'Visa Inc.'],
        ];

        foreach ($stocks as $stock) {
            Stock::updateOrCreate(
                ['symbol' => $stock['symbol']],
                ['name' => $stock['name']]
            );
        }

        $this->command->info('Stocks seeded successfully.');
    }
}
