<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        // Upcoming schedules
        $schedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.status', 'upcoming')
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->orderBy('schedules.schedule_date', 'asc')
            ->orderBy('schedules.start_time', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.available_slots',
                'schedules.capacity',
                'classes.class_name',
                'classes.description',
                'users.name as coach_name'
            )
            ->get();

        // Active promotions
        $promotions = DB::table('promotions')
            ->where('is_active', 1)
            ->orderBy('promo_id', 'asc')
            ->get();

        return view('pages.home', [
            'schedules' => $schedules,
            'promotions' => $promotions,
            'user_name' => Session::get('user_name', 'Member'),
        ]);
    }
}
