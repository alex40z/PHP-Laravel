<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;

class BookingController extends Controller
{
    public function index()
    {
        return view('bookings.index',
            ['bookings' => Booking::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->paginate(10)]);
    }

    public function show(Request $request, int $id)
    {
        $booking = Booking::find($id);
        return view('bookings.show', ['booking' => $booking]);
    }

    public function store(Request $request)
    {
        try {
            $booking = Booking::create([
                'room_id' => $request->room_id,
                'user_id' => Auth::user()->id,
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
                'days' => floor((strtotime($request->finished_at) - strtotime($request->started_at)) / 3600 / 24),
                'price' => Room::find($request->room_id)->price
            ]);
            Mail::to(Auth::user()->email)->send(new BookingConfirmation($booking));
            return redirect()->route('bookings.index', [], 201);
        } catch(QueryException $e) {
            if ($e->getCode() == 45000) {
                $messages = new MessageBag();
                $messages->add('form', 'Номер уже забронирован на указанные даты. Обновите страницу и повторите бронирование.');
                return redirect()->route('hotels.show', ['id' => Room::find($request->room_id)->hotel_id], 205)->withErrors($messages);
            } else {
                throw $e;
            }
        }
    }
}
