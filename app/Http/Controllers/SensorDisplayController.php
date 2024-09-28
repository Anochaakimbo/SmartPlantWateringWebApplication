<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorDisplayController extends Controller
{
    public function index()
{
    $sensorData = SensorData::latest()->first();
    return view('sensor-display', ['sensorData' => $sensorData]);
}
}
