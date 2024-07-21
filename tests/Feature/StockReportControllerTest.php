<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReportControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_empty_array_when_no_stocks_exist()
    {
        $response = $this->getJson('/api/get-stocks-report');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_data_with_na_when_stocks_exist_but_no_prices()
    {
        Stock::factory()->create(['symbol' => 'AAPL', 'name' => 'Apple Inc.']);

        $response = $this->getJson('/api/get-stocks-report');

        $response->assertStatus(200)
            ->assertJson([[
                'symbol' => 'AAPL',
                'name' => 'Apple Inc.',
                'price' => 'N/A',
                'percentage_change' => 'N/A',
                'timestamp' => 'N/A',
            ]]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_correct_data_when_stocks_have_less_than_two_prices()
    {
        $stock = Stock::factory()->create(['symbol' => 'AAPL', 'name' => 'Apple Inc.']);
        $stockPrice = StockPrice::factory()->create(['stock_id' => $stock->id, 'price' => 150]);

        $response = $this->getJson('/api/get-stocks-report');

        $response->assertStatus(200)
            ->assertJson([[
                'symbol' => 'AAPL',
                'name' => 'Apple Inc.',
                'price' => '150.00',
                'percentage_change' => 'N/A',
                'timestamp' => $stockPrice->created_at->toISOString(),
            ]]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calculates_percentage_change_correctly_for_stocks_with_two_prices()
    {
        $stock = Stock::factory()->create(['symbol' => 'AAPL', 'name' => 'Apple Inc.']);
        StockPrice::factory()->create(['stock_id' => $stock->id, 'price' => 100, 'created_at' => now()->subDay()]);
        $stockPrice = StockPrice::factory()->create(['stock_id' => $stock->id, 'price' => 110, 'created_at' => now()]);

        $response = $this->getJson('/api/get-stocks-report');

        $response->assertStatus(200)
            ->assertJson([[
                'symbol' => 'AAPL',
                'name' => 'Apple Inc.',
                'price' => 110,
                'percentage_change' => 10.0,
                'timestamp' => $stockPrice->created_at->toISOString(),
            ]]);
    }
}
