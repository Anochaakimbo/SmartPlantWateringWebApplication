<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class CameraController extends Controller
{
    public function saveSnapshot(Request $request)
{
    Log::info('Received snapshot request');
    try {
        $image = $request->input('image');
        if (empty($image)) {
            throw new \Exception('No image data received');
        }

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.png';

        $directory = public_path('snapshots');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $fullPath = $directory . '/' . $imageName;
        if (File::put($fullPath, base64_decode($image))) {
            Log::info('Image saved successfully: ' . $fullPath);
            return response()->json(['success' => 'Image saved successfully.']);
        } else {
            throw new \Exception('Failed to save image');
        }
    } catch (\Exception $e) {
        Log::error('Error saving snapshot: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
