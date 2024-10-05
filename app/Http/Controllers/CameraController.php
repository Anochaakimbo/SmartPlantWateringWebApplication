<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CameraController extends Controller
{
    public function saveSnapshot(Request $request)
    {
        $image = $request->input('image');  // รับภาพที่ส่งมา
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.png';

        // ตรวจสอบว่ามีโฟลเดอร์หรือยัง ถ้าไม่มีก็สร้าง
        $directory = public_path('snapshots');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // บันทึกภาพลงในโฟลเดอร์ public/snapshots
        File::put($directory . '/' . $imageName, base64_decode($image));

        return response()->json(['success' => 'Image saved successfully.']);
    }
}
