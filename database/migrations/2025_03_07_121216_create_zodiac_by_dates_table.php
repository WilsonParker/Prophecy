<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zodiac_by_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->nullable(false)->comment('year');
            $table->unsignedTinyInteger('month')->nullable(false)->comment('month');
            $table->unsignedTinyInteger('day')->nullable(false)->comment('day');
            // $table->string('zodiac')->nullable(false)->comment('day');
            $table->timestamps();
            $table->index(['year', 'month', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zodiac_by_date');
    }
};
