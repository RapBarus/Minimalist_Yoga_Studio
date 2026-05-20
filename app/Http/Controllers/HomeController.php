<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $schedules = DB::table('vw_available_schedules')
            ->where('schedule_date', '>=', now()->toDateString())
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $userId = Session::get('user_id');

        // Get schedules the user already booked
        $bookedScheduleIds = DB::table('bookings')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'attended'])
            ->pluck('schedule_id')
            ->toArray();

        // Mark each schedule as booked
        $schedules = $schedules->map(function ($schedule) use ($bookedScheduleIds) {
            $schedule->already_booked = in_array($schedule->schedule_id, $bookedScheduleIds);
            return $schedule;
        });

        $promotions = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_packages.is_active', 1)
            ->orderBy('membership_packages.package_id', 'asc')
            ->select('membership_packages.*', 'classes.class_name')
            ->get()
            ->map(function ($package) {
                $package->title = $package->name;
                $package->coach_name = null;
                $package->coach_id = null;
                $package->schedule_date = null;
                $package->start_time = null;
                $package->end_time = null;
                $package->promo_price = number_format($package->price, 0, ',', '.');
                $package->masa_aktif = $package->validity_months * 30 . ' Hari';
                $package->promo_id = $package->package_id;
                return $package;
            });

        return view('pages.home', [
            'schedules'  => $schedules,
            'promotions' => $promotions,
            'user_name'  => Session::get('user_name', 'Member'),
        ]);
    }

    public function coachProfile($coachId)
    {
        if (!is_numeric($coachId)) {
            abort(404);
        }

        $coach = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->where('coaches.coach_id', $coachId)
            ->select(
                'coaches.coach_id',
                'classes.class_name as specialization',
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
