<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->orderBy('schedules.schedule_date', 'desc')
            ->orderBy('schedules.start_time', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.capacity',
                'schedules.available_slots',
                'schedules.status',
                'classes.class_name',
                'users.name as coach_name'
            )
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name', 'coaches.specialization')
            ->get();

        return view('admin.schedules', [
            'schedules' => $schedules,
            'classes'   => $classes,
            'coaches'   => $coaches,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'      => 'required|integer',
            'coach_id'      => 'required|integer',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'capacity'      => 'required|integer|min:1',
        ], [
            'class_id.required'      => 'Pilih kelas.',
            'coach_id.required'      => 'Pilih coach.',
            'schedule_date.required' => 'Tanggal wajib diisi.',
            'schedule_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'start_time.required'    => 'Waktu mulai wajib diisi.',
            'end_time.required'      => 'Waktu selesai wajib diisi.',
            'end_time.after'         => 'Waktu selesai harus setelah waktu mulai.',
            'capacity.required'      => 'Kapasitas wajib diisi.',
        ]);

        DB::table('schedules')->insert([
            'class_id'        => $request->class_id,
            'coach_id'        => $request->coach_id,
            'schedule_date'   => $request->schedule_date,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'capacity'        => $request->capacity,
            'available_slots' => $request->capacity,
            'status'          => 'upcoming',
            'created_at'      => now(),
        ]);

        return redirect()->route('admin.schedules')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function destroy($scheduleId)
    {
        // Check if any bookings exist
        $hasBookings = DB::table('bookings')->where('schedule_id', $scheduleId)->exists();
        if ($hasBookings) {
            return redirect()->route('admin.schedules')
                ->withErrors(['error' => 'Jadwal tidak bisa dihapus karena sudah ada booking.']);
        }

        DB::table('schedules')->where('schedule_id', $scheduleId)->delete();

        return redirect()->route('admin.schedules')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function updateStatus(Request $request, $scheduleId)
    {
        $request->validate(['status' => 'required|in:upcoming,completed,cancelled']);

        DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.schedules')
            ->with('success', 'Status jadwal berhasil diperbarui.');
    }
}
