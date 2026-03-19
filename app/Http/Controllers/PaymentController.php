<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function show($schedule_id)
    {
        $schedule = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.schedule_id', $schedule_id)
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.available_slots',
                'classes.class_name',
                'users.name as coach_name',
                'coaches.rate_per_class as price'
            )
            ->first();

        if (!$schedule) {
            return redirect()->route('home')->withErrors(['error' => 'Jadwal tidak ditemukan.']);
        }

        return view('pages.payment', [
            'schedule' => $schedule,
        ]);
    }

    public function process(Request $request)
    {
        return redirect()->route('home')->with('success', 'Pembayaran sedang diproses!');
    }
}
