<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\WateringHistoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route สำหรับการรับข้อมูลจาก ESP8266 และบันทึกลงฐานข้อมูล
Route::post('/sensor', [SensorController::class, 'store']);

// Route สำหรับการดึงค่าขอบเขตความชื้นจากฐานข้อมูล (ส่งให้ ESP8266)
Route::get('/moisture-threshold', [SensorController::class, 'getMoistureThreshold']);

Route::post('/watering-history', [WateringHistoryController::class, 'store']);



