<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockPrice;

class StockReportCalculationService
{
    public function calculateStocks()
    {
        $stocks = Stock::with(['prices' => function ($query) {
            $query->orderByDesc('created_at')->take(2);
        }])->get();

        return $stocks->map(function ($stock) {
            $stocksDetails = $stock->prices->take(2);

            if ($stocksDetails->count() == 2) {
                $priceCurrent = $stocksDetails->first()->price;
                $pricePrevious = $stocksDetails->last()->price;
                $timestamp = $stocksDetails->first()->created_at;

                if ($pricePrevious != 0) {
                    $percentageChange = (($priceCurrent - $pricePrevious) / $pricePrevious) * 100;
                } else {
                    $percentageChange = 'N/A';
                }
            } else {
                $priceCurrent = $stocksDetails->first()->price ?? 'N/A';
                $percentageChange = 'N/A';
                $timestamp = $stocksDetails->first()->created_at ?? 'N/A';
            }

            return [
                'symbol' => $stock->symbol,
                'name' => $stock->name,
                'price' => $priceCurrent,
                'percentage_change' => $percentageChange,
                'timestamp' => $timestamp,
            ];
        });
    }

}
