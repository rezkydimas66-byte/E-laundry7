<?php
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::apiResource('orders', OrderController::class);
Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus']);
