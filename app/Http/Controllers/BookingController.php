<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(request $request){
        $data = $request->validate([
            'title' =>'required|string',
            'description' =>'required|string',
            'start_time' =>'required|date',
            'end_time' =>'required|date:start_time',
            'user_id' =>'required|exists:users,id',
            'client_id' =>'required|exists:clients,id',
        ]);

        if(Booking::hasOverlap($data['user_id'],$data['start_time'],$data['end_time'])){
            return respose()->json(['error' => 'overlapping booking detected'], 422);
        }

        $booking = Booking::create($data);
        return response()->json($booking,201);
    }

    public function index(request $request){
        $weekDate = Carbon::parse($request->query('week', now()));
        $startofWeek = $weekDate->startOfWeek(Carbon::MONDAY);
        $endofWeek = $weekDate->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::with(['user','client'])->
            whereBetween('start_time', [$startofWeek, $endofWeek])->get();

        return response()->json($bookings);
    }
}
