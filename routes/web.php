<?php
use App\Http\Controllers\WateringHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\CameraController;
Route::get('/', function () {
    return view('welcome');
});

// ใช้ Route นี้สำหรับแสดงหน้าฟอร์มและข้อมูลเซนเซอร์
Route::get('/sensor-display', [SensorController::class, 'showThresholdForm'])->name('sensor.display');

// ใช้ Route นี้สำหรับอัปเดตค่า moisture threshold
Route::post('/threshold', [SensorController::class, 'updateThreshold'])->name('update.threshold');

Route::get('/watering-history', [WateringHistoryController::class, 'index']);

Route::get('/test-daily-summary', [WateringHistoryController::class,'sendDailyWateringSummary']);

Route::post('/save-snapshot', [CameraController::class, 'saveSnapshot'])->name('save.snapshot');

