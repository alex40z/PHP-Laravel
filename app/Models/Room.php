<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'poster_url',
        'floor_area',
        'type',
        'price',
        'hotel_id'
    ];

    public function getFacilitiesAttribute()
    {
        return Facility::select('name')
            ->join('facility_room', 'facilities.id', '=', 'facility_room.facility_id')
            ->where('room_id', $this->id)->get();
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
