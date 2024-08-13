<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("CREATE TRIGGER bookings_validate
            BEFORE INSERT ON bookings
            FOR EACH ROW
            BEGIN
                IF EXISTS(SELECT * FROM bookings WHERE room_id = NEW.room_id AND started_at < NEW.finished_at and finished_at > NEW.started_at) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Booking duplicate!';
                END IF;
            END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER bookings_validate');
    }
};
