<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityHotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'hotel_id'
    ];
}
