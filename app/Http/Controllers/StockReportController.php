<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetStockReportRequest;
use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class StockReportController extends Controller
{
    public function getStockReport(GetStockReportRequest $request): JsonResponse
    {
        $data = Cache::get("stock_{$request->get('symbol')}_latest");

        if ($data) {
            return response()->json([
                'symbol' => $request->get('symbol'),
                'price' => $data['price'],
                'percentage_change' => $data['percentage_change'],
                'timestamp' => $data['timestamp'],
            ]);
        } else {
            return response()->json([
                'message' => 'Stock data not available in cache'
            ], 404);
        }
    }

    public function getAllStockReports(): JsonResponse
    {
        $stocks = Stock::all();
        $reports = $stocks->map(function ($stock) {
            $stocksDetails = StockPrice::where('stock_id', $stock->id)
                ->orderByDesc('created_at')
                ->take(2)
                ->get();

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

        return response()->json($reports);
    }

}
