<?php

use App\Models\Facility;
use App\Models\Hotel;
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
        Schema::create('facility_hotel', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Facility::class)->references('id')->on('facilities');
            $table->foreignIdFor(Hotel::class)->references('id')->on('hotels');
            $table->unique(['facility_id', 'hotel_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_hotel');
    }
};
