<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');

        $baseQuery = DB::table('vw_customer_booking_history')
            ->where('user_id', $userId)
            ->select(
                'booking_id',
                'booking_status',
                'schedule_date',
                'start_time',
                'end_time',
                'class_name',
                'coach_id',
                'rate_per_class',
                'coach_name',
                'amount'
            );

        // Active: upcoming schedules that are confirmed/pending
        $activeBookings = (clone $baseQuery)
            ->where('schedule_date', '>=', now()->toDateString())
            ->whereIn('booking_status', ['confirmed'])   // Remove 'pending' if not used
            ->orderBy('schedule_date', 'asc')
            ->get();

        // History: past schedules or cancelled
        $historyBookings = (clone $baseQuery)
            ->where(function ($q) {
                $q->where('schedule_date', '<', now()->toDateString())
                    ->orWhere('booking_status', 'cancelled');
            })
            ->orderBy('schedule_date', 'desc')
            ->get();

        return view('pages.activity', compact('activeBookings', 'historyBookings'));
    }
}
