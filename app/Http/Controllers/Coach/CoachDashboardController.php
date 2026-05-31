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
        if (!$coach) return redirect()->route('login');

        $coachId = $coach->coach_id;
        $filter = $request->get('filter', 'all');

        $query = DB::table('vw_coach_schedule')
            ->where('coach_id', $coachId)
            ->where('schedule_date', '>=', now()->toDateString())
            ->orderBy('schedule_date', 'asc');

        // Apply filter
        if ($filter === 'today') {
            $query->whereDate('schedule_date', today());
        } elseif ($filter === 'week') {
            $query->where('schedule_date', '<=', now()->addDays(7)->toDateString());
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

        $existingPhoto = DB::table('attendance')
            ->join('bookings', 'attendance.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.schedule_id', $scheduleId)
            ->whereNotNull('attendance.photo_url')
            ->value('attendance.photo_url');


        return view('coach.coach_schedule_detail', compact(
            'schedule',
            'participants',
            'hadir',
            'tidakHadir',
            'existingPhoto'
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
            $url = asset('storage/' . $path);

            $bookings = DB::table('bookings')
                ->where('schedule_id', $scheduleId)
                ->select('booking_id', 'status')
                ->get();

            foreach ($bookings as $booking) {
                DB::table('attendance')->updateOrInsert(
                    ['booking_id' => $booking->booking_id],
                    [
                        'coach_verification' => $booking->status === 'attended' ? 1 : 0,
                        'admin_verification' => 0,
                        'check_in_time' => $booking->status === 'attended' ? now() : null,
                        'photo_url' => $url,
                        'photo_uploaded_at' => now(),
                    ]
                );
            }
        }

        // Mark schedule as completed
        DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->update([
                'status' => 'completed',
                // 'updated_at' => now(),
            ]);

        return redirect()->route('coach.schedule.detail', $scheduleId)
            ->with('success', 'Jadwal berhasil diupdate!');
    }
    public function deletePhoto($scheduleId)
    {
        $userId = Session::get('user_id');
        $coach = DB::table('coaches')->where('user_id', $userId)->first();
        if (!$coach)
            return redirect()->route('login');

        DB::table('attendance')
            ->join('bookings', 'attendance.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.schedule_id', $scheduleId)
            ->update(['attendance.photo_url' => null, 'attendance.photo_uploaded_at' => null]);

        DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->update(['status' => 'upcoming']);

        return redirect()->route('coach.schedule.detail', $scheduleId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
