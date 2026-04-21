<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CoachDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Session::get('user_id');
        $coach = DB::table('coaches')->where('user_id', $userId)->first();
        if (!$coach)
            return redirect()->route('login');

        $coachId = $coach->coach_id;
        $filter = $request->get('filter', 'all');

        $query = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
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
                'schedules.capacity',
                'classes.class_name',
                'coaches.rate_per_class'
            );

        // Apply filter
        if ($filter === 'week') {
            $query->where('schedules.schedule_date', '<=', now()->endOfWeek()->toDateString());
        } elseif ($filter === 'month') {
            $query->where('schedules.schedule_date', '<=', now()->endOfMonth()->toDateString());
        }

        $schedules = $query->get();

        return view('coach.coach_dashboard', compact('schedules', 'filter'));
    }

    public function scheduleDetail($scheduleId)
    {
        $userId = Session::get('user_id');
        $coach = DB::table('coaches')->where('user_id', $userId)->first();
        if (!$coach)
            return redirect()->route('login');

        $schedule = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.schedule_id', $scheduleId)
            ->where('schedules.coach_id', $coach->coach_id)
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.capacity',
                'schedules.available_slots',
                'schedules.status',
                'classes.class_name',
                'coaches.rate_per_class'
            )
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->first();

        abort_if(!$schedule, 404);

        // Get all participants for this schedule
        $participants = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.user_id')
            ->where('bookings.schedule_id', $scheduleId)
            ->select(
                'bookings.booking_id',
                'bookings.status',
                'users.name'
            )
            ->get();

        $hadir = $participants->where('status', 'attended');
        $tidakHadir = $participants->whereNotIn('status', ['attended']);

        return view('coach.coach_schedule_detail', compact(
            'schedule',
            'participants',
            'hadir',
            'tidakHadir'
        ));
    }

    public function updateSchedule(Request $request, $scheduleId)
    {
        $userId = Session::get('user_id');
        $coach = DB::table('coaches')->where('user_id', $userId)->first();
        if (!$coach)
            return redirect()->route('login');

        // Update attendance for each booking
        if ($request->has('attendance')) {
            foreach ($request->attendance as $bookingId => $status) {
                $bookingStatus = $status === 'hadir' ? 'attended' : 'confirmed';
                DB::table('bookings')
                    ->where('booking_id', $bookingId)
                    ->update([
                        'status' => $bookingStatus,
                        // 'updated_at' => now(),
                    ]);
            }
        }

        // Handle file upload
        if ($request->hasFile('bukti_hadir')) {
            $file = $request->file('bukti_hadir');
            $path = $file->store('bukti_hadir', 'public');
        }

        // Mark schedule as completed
        DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->update([
                'status' => 'completed',
                // 'updated_at' => now(),
            ]);

        return redirect()->route('coach.dashboard')
            ->with('success', 'Jadwal berhasil diupdate!');
    }
}
