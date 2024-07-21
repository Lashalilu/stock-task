<?php

use App\Http\Controllers\StockReportController;
use Illuminate\Support\Facades\Route;

Route::get('/get-stocks-report', [StockReportController::class, 'getAllStockReports']);
