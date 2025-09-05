<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\carbon;

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

        // detect overlapping bookings
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

    // get weekly bookings
    public function weekly(Request $request)
    {
        $weekDate = $request->query('week');

        if (!$weekDate) {
            return response()->json(['error' => 'Week query parameter is required'], 422);
        }

        $startOfWeek = Carbon::parse($weekDate)->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::parse($weekDate)->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::with(['user', 'client'])
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek]);

        // filter
        if ($request->has('user_id') && $request->user_id) {
            $bookings->where('user_id', $request->user_id);
        }

        if ($request->has('client_id') && $request->client_id) {
            $bookings->where('client_id', $request->client_id);
        }

        return response()->json($bookings->orderBy('start_time')->get(), 200);
    }
}
