<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'user_id'    => 'required|exists:users,id',
            'client_id'  => 'required|exists:clients,id',
        ]);

        // Check overlapping bookings
        $overlap = Booking::where('user_id', $request->user_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function($query2) use ($request) {
                        $query2->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json(['error' => 'Overlapping booking detected'], 422);
        }

        $booking = Booking::create($request->all());

        return response()->json($booking, 201);
    }

    // Retrieve weekly bookings
    public function weekly(Request $request)
    {
        $weekStart = $request->query('week');

        if (!$weekStart) {
            return response()->json(['error' => 'Week query parameter is required'], 422);
        }

        $weekEnd = date('Y-m-d H:i:s', strtotime($weekStart . ' +6 days'));
        $bookings = Booking::whereBetween('start_time', [$weekStart, $weekEnd])->get();

        return response()->json($bookings, 200);
    }
}
