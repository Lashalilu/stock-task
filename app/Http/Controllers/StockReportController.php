<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockReportResource;
use App\Services\StockReportCalculationService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;


class StockReportController extends Controller
{

    public function __construct(protected StockReportCalculationService $stockReportCalculationService)
    {
    }

    public function getAllStockReports(): AnonymousResourceCollection
    {
        $stocks = Cache::remember('all_stock_reports', 1, function () {
            return $this->stockReportCalculationService->calculateStocks();
        });

        return StockReportResource::collection($stocks);
    }
}
