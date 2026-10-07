<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 撮影の空き枠。1行 = 1日の午前または午後の「空きあり」。行が無い枠は予約不可。
     */
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('slot', 2)->comment('am=午前 / pm=午後');
            $table->timestamps();

            $table->unique(['date', 'slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
