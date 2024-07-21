<?php

use App\Http\Controllers\StockReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/get-stock-details', [StockReportController::class, 'getStockReport']);
Route::get('/get-stocks-report', [StockReportController::class, 'getAllStockReports']);
