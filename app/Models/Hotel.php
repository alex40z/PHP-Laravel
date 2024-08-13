<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'poster_url',
        'address',
        'category'
    ];

    public function getPriceAttribute()
    {
        return Room::where('hotel_id', $this->id)->min('price');
    }

    public function getFacilitiesAttribute()
    {
        return Facility::select('name')
            ->join('facility_hotel', 'facilities.id', '=', 'facility_hotel.facility_id')
            ->where('hotel_id', $this->id)->get();
    }
}
