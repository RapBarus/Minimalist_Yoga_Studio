<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoachProfileController extends Controller
{
    public function index(Request $request)
    {
        $userId = Session::get('user_id');
        $coach = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->where('coaches.user_id', $userId)
            ->select(
                'coaches.coach_id',
                'coaches.rate_per_class',
                'coaches.years_experience',
                'coaches.bio',
                'users.name',
                'users.phone_number',
                'classes.class_name'
            )
            ->first();

        if (!$coach)
            return redirect()->route('login');

        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $totalKelas = DB::table('schedules')
            ->where('coach_id', $coach->coach_id)
            ->where('status', 'completed')
            ->whereBetween('schedule_date', [$from, $to])
            ->count();

        $totalPeserta = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->where('schedules.coach_id', $coach->coach_id)
            ->whereIn('bookings.status', ['confirmed', 'attended'])
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->count();

        $totalPendapatan = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->where('schedules.coach_id', $coach->coach_id)
            ->whereIn('transactions.status', ['settlement', 'paid'])
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->sum('transactions.amount');


        // Chart data — pendapatan per kelas
        $chartData = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.coach_id', $coach->coach_id)
            ->whereIn('transactions.status', ['settlement', 'paid'])
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->select('classes.class_name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('classes.class_name')
            ->get();

        $chartLabels = $chartData->pluck('class_name')->toArray();
        $chartValues = $chartData->pluck('total')->toArray();

        return view('coach.coach_profile', compact(
            'coach',
            'from',
            'to',
            'totalKelas',
            'totalPeserta',
            'totalPendapatan',
            'chartLabels',
            'chartValues'
        ));
    }
}
