<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WateringHistory extends Model
{
    use HasFactory;
    protected $table = 'watering_history';  // ชื่อตารางที่โมเดลนี้อ้างอิง
    protected $fillable = ['watering_time'];
}
