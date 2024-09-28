<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pump_control', function (Blueprint $table) {
            $table->id();
            $table->boolean('pump_state')->default(false);  // สถานะของปั๊มน้ำ (เปิด/ปิด)
            $table->boolean('manual_control')->default(false);  // การควบคุมด้วยตนเอง (เปิด/ปิด)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pump_control');
    }
};
