<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\WateringHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WateringHistoryController extends Controller
{
    public function store(Request $request)
    {
        // บันทึกเวลาที่ปั๊มน้ำทำงาน
        $watering = new WateringHistory();
        $watering->watering_time = now();
        $watering->save();

        return response()->json(['message' => 'Watering time recorded'], 200);
    }
    public function index()
    {
        $wateringHistory = WateringHistory::all();  // ดึงข้อมูลทั้งหมดจากตาราง watering_history
        return view('watering_history', compact('wateringHistory'));  // ส่งข้อมูลไปยัง view
    }

    public function sendDailyWateringSummary()
    {
        try {
            $oneDayAgo = Carbon::now()->subDay();
            $todayWateringHistory = WateringHistory::where('watering_time', '>=', $oneDayAgo)->get();

            // กำหนดข้อความเบื้องต้นสำหรับการแจ้งเตือน
            if ($todayWateringHistory->isEmpty()) {
                $message = "สรุปการรดน้ำประจำวัน: ไม่มีการรดน้ำในรอบ 24 ชั่วโมงที่ผ่านมา";
            } else {
                $message = "สรุปการรดน้ำประจำวัน:\n";
                foreach ($todayWateringHistory as $history) {
                    $message .= "- รดน้ำเวลา: " . $history->watering_time . "\n";
                }
            }

            // แจ้งเตือน LINE ว่าจะมีการส่งข้อความ
            Log::info('Preparing to send message: ' . $message);

            // ตั้งค่า LINE Notify token ตรงๆ
            $lineToken = 'rTRLbTUJyPe2aXshs8TiT4ZkWKmsH2wLJYftBNrx7qU'; // เปลี่ยนเป็นโทเคนของคุณ

            $client = new Client();
            $response = $client->post('https://notify-api.line.me/api/notify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $lineToken,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'message' => $message,
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                Log::info('Daily watering summary sent successfully: ' . $message);
                return "ส่งสรุปประจำวันสำเร็จแล้ว!";
            } else {
                Log::error('Failed to send daily watering summary. Response: ' . $response->getBody());
                return "การส่งสรุปประจำวันล้มเหลว! โปรดตรวจสอบ log สำหรับรายละเอียดเพิ่มเติม";
            }
        } catch (\Exception $e) {
            Log::error('Error in sendDailyWateringSummary: ' . $e->getMessage());
            return "เกิดข้อผิดพลาดในการส่งสรุปประจำวัน: " . $e->getMessage();
        }
    }
}
