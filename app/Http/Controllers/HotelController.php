<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        if (!isset($request->clear_filter)) {
            $category = $request->get('category');
            $min_price = $request->get('min_price');
            $max_price = $request->get('max_price');
            $facilities = $request->get('facilities');
        } else {
            $facilities = null;
        }

        $hotels = Hotel::join('rooms', 'hotels.id', '=', 'rooms.hotel_id')->select('hotels.*')->distinct('hotels.id');
        if (isset($category)) {
            $hotels->where('category', '=', $category);
        }
        if (isset($min_price)) {
            $hotels->where('price', '>=', $min_price);
        }
        if (isset($max_price)) {
            $hotels->where('price', '<=', $max_price);
        }
        if ($facilities) {
            $hotels->whereExists(function ($query) use ($facilities) {
                $query->select(DB::raw(1))->from('facility_hotel')
                    ->whereRaw('hotel_id = hotels.id')->whereIn('facility_id', $facilities)
                    ->having(DB::raw('count(*)'), '=', sizeof($facilities));
            });
        }

        $facilitiesList = Facility::join('facility_hotel', 'facilities.id', '=', 'facility_hotel.facility_id')
            ->select('facilities.*')->distinct()->orderBy('name')->get();

        return view('hotels.index', ['hotels' => $hotels->paginate(10), 'facilities_list' => $facilitiesList]);
    }

    public function show(Request $request, int $id)
    {
        $hotel = Hotel::find($id);
        $startDate = $request->get('start_date', \Carbon\Carbon::now()->format('Y-m-d'));
        $endDate = $request->get('end_date', \Carbon\Carbon::now()->addDay()->format('Y-m-d'));

        $rooms = Room::fromQuery('select r.* from rooms r left join bookings b on r.id = b.room_id
            and started_at < :end_date and finished_at > :start_date
            where hotel_id = :hotel_id and b.id is null order by price',
            ['end_date' => $endDate, 'start_date' => $startDate, 'hotel_id' => $id]);

        return view('hotels.show', ['hotel' => $hotel, 'rooms' => $rooms]);
    }
}
