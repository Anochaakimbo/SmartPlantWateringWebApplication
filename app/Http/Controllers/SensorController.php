<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SensorData;  // อย่าลืมใช้ Model ที่สร้างขึ้นมา

class SensorController extends Controller
{
    public function store(Request $request)
{
    // รับค่าจาก ESP8266
    $moistureLevel = $request->input('moisture_level');
    $pumpState = $request->input('pump_state');
    $waterLevel = $request->input('water_level');

    // ตรวจสอบและบันทึกข้อมูล
    if ($moistureLevel && isset($pumpState) && isset($waterLevel)) {
        SensorData::create([
            'moisture_level' => $moistureLevel,
            'pump_state' => $pumpState,
            'water_level' => $waterLevel
        ]);
        return response()->json(['message' => 'Data received and saved']);
    }

    return response()->json(['message' => 'Data missing'], 400);
}
public function updateThreshold(Request $request)
{
    $request->validate([
        'moistureThreshold' => 'required|integer|min:0|max:1023', // ตรวจสอบค่าที่ถูกต้อง
    ]);

    // อัปเดตค่าขอบเขตในฐานข้อมูล
    DB::table('settings')->updateOrInsert(
        ['key' => 'moisture_threshold'],
        ['value' => $request->input('moistureThreshold')]
    );

    return redirect()->route('sensor.display')->with('success', 'อัพเดทค่าความชื้นสำเร็จ!');
}
public function showThresholdForm()
{
    // ดึงค่าขอบเขตปัจจุบันจากฐานข้อมูลหรือค่าเริ่มต้น
    $currentThreshold = DB::table('settings')->where('key', 'moisture_threshold')->value('value') ?? 400;

    // ดึงข้อมูลเซนเซอร์จากฐานข้อมูลเพื่อแสดงในหน้า sensor-display
    $sensorData = SensorData::latest()->first();

    return view('sensor-display', compact('sensorData', 'currentThreshold'));
}
public function getMoistureThreshold()
{
    // ดึงค่าขอบเขตความชื้นจากฐานข้อมูล หรือใช้ค่าเริ่มต้น (400)
    $moistureThreshold = DB::table('settings')->where('key', 'moisture_threshold')->value('value') ?? 400;

    // ส่งค่าในรูปแบบ JSON ให้ ESP8266
    return response()->json(['moisture_threshold' => $moistureThreshold]);
}



}
