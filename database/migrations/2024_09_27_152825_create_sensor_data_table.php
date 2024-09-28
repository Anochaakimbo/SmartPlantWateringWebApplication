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
    Schema::create('sensor_data', function (Blueprint $table) {
        $table->id();
        $table->integer('moisture_level');
        $table->boolean('pump_state');  // สถานะของปั๊มน้ำ
        $table->boolean('water_level');  // ระดับน้ำ (true/false)
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
