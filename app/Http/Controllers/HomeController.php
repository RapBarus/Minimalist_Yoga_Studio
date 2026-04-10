<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
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
                'coaches.coach_id',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->get();

        $promotions = DB::table('promotions')
            ->join('coaches', 'promotions.coach_name', '=', DB::raw('(SELECT u.name FROM users u JOIN coaches c2 ON c2.user_id = u.user_id WHERE c2.coach_id = coaches.coach_id LIMIT 1)'))
            ->where('promotions.is_active', 1)
            ->orderBy('promotions.promo_id', 'asc')
            ->select('promotions.*', 'coaches.coach_id')
            ->get();


        if ($promotions->isEmpty()) {
            $promotions = DB::table('promotions')
                ->where('is_active', 1)
                ->orderBy('promo_id', 'asc')
                ->get()
                ->map(function ($promo) {
                    $coach = DB::table('coaches')
                        ->join('users', 'coaches.user_id', '=', 'users.user_id')
                        ->where('users.name', $promo->coach_name)
                        ->select('coaches.coach_id')
                        ->first();
                    $promo->coach_id = $coach ? $coach->coach_id : null;
                    return $promo;
                });
        }

        return view('pages.home', [
            'schedules' => $schedules,
            'promotions' => $promotions,
            'user_name' => Session::get('user_name', 'Member'),
        ]);
    }

    public function coachProfile($coachId)
    {
        if (!is_numeric($coachId)) {
            abort(404);
        }
        $coach = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('coaches.coach_id', $coachId)
            ->select(
                'coaches.coach_id',
                'coaches.specialization',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->first();

        abort_if(!$coach, 404);

        $coachSchedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.coach_id', $coachId)
            ->where('schedules.status', 'upcoming')
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->orderBy('schedules.schedule_date', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.available_slots',
                'classes.class_name'
            )
            ->get();

        return view('pages.coach_profile', compact('coach', 'coachSchedules'));
    }
}
