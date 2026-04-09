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

        $baseQuery = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('transactions', 'bookings.booking_id', '=', 'transactions.booking_id')
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.booking_id',
                'bookings.status as booking_status',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'classes.class_name',
                'coaches.coach_id',
                'coaches.rate_per_class',
                'users.name as coach_name',
                'transactions.amount'
            );

        // Active: upcoming schedules that are confirmed/pending
        $activeBookings = (clone $baseQuery)
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->whereIn('bookings.status', ['confirmed', 'pending'])
            ->orderBy('schedules.schedule_date', 'asc')
            ->get();

        // History: past schedules or cancelled
        $historyBookings = (clone $baseQuery)
            ->where(function ($q) {
                $q->where('schedules.schedule_date', '<', now()->toDateString())
                    ->orWhere('bookings.status', 'cancelled');
            })
            ->orderBy('schedules.schedule_date', 'desc')
            ->get();

        return view('pages.activity', compact('activeBookings', 'historyBookings'));
    }
}
