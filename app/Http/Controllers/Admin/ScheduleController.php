<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ScheduleController extends Controller
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
                'schedules.capacity',
                'schedules.available_slots',
                'schedules.status',
                'classes.class_name',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name', 'coaches.specialization')
            ->get();

        // Dates that have schedules for calendar highlighting
        $scheduleDates = DB::table('schedules')
            ->where('status', 'upcoming')
            ->pluck('schedule_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        return view('admin.schedules', [
            'schedules' => $schedules,
            'classes' => $classes,
            'coaches' => $coaches,
            'scheduleDates' => $scheduleDates,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer',
            'coach_id' => 'required|integer',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
        ], [
            'class_id.required' => 'Pilih kelas.',
            'coach_id.required' => 'Pilih coach.',
            'schedule_date.required' => 'Tanggal wajib diisi.',
            'schedule_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'capacity.required' => 'Kapasitas wajib diisi.',
            'capacity.max' => 'Kapasitas maksimal 100 orang.',
        ]);

        // Max 240 minutes duration
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $duration = $start->diffInMinutes($end);

        if ($duration > 240) {
            return back()
                ->withErrors(['end_time' => 'Durasi kelas maksimal 240 menit (4 jam).'])
                ->withInput();
        }

        DB::table('schedules')->insert([
            'class_id' => $request->class_id,
            'coach_id' => $request->coach_id,
            'schedule_date' => $request->schedule_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'available_slots' => $request->capacity,
            'status' => 'upcoming',
            'created_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function viewJadwal($scheduleId)
    {
        $schedule = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.schedule_id', $scheduleId)
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.capacity',
                'schedules.available_slots',
                'schedules.status',
                'classes.class_name',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->first();

        abort_if(!$schedule, 404);

        // Get participants: bookings + transactions for this schedule
        $participants = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.user_id')
            ->leftJoin('transactions', 'bookings.booking_id', '=', 'transactions.booking_id')
            ->where('bookings.schedule_id', $scheduleId)
            ->select(
                'users.name',
                'users.phone_number',
                'transactions.payment_type',
                'transactions.status as transaction_status',
                'transactions.amount'
            )
            ->get();

        return view('admin.view_jadwal', compact('schedule', 'participants'));
    }

    public function addPeserta(Request $request, $scheduleId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'payment_type' => 'required|in:cash,qris,transfer',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{8,15}$/',
            'amount' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama peserta wajib diisi.',
            'payment_type.required' => 'Metode pembayaran wajib dipilih.',
            'amount.required' => 'Harga wajib diisi.',
        ]);

        $schedule = DB::table('schedules')->where('schedule_id', $scheduleId)->first();
        abort_if(!$schedule, 404);

        if ($schedule->available_slots <= 0) {
            return back()->withErrors(['error' => 'Kuota jadwal sudah penuh.']);
        }

        // Find or create user by name (walk-in)
        $user = DB::table('users')->where('name', $request->name)->first();

        if (!$user) {
            // Create a walk-in customer account
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'phone_number' => $request->filled('phone_number')
                    ? (str_starts_with($request->phone_number, '0')
                        ? '+62' . substr($request->phone_number, 1)
                        : (str_starts_with($request->phone_number, '+')
                            ? $request->phone_number
                            : '+' . $request->phone_number))
                    : null,
                'email' => null,
                'password_hash' => bcrypt('walkin' . time()),
                'role' => 'customer',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->user_id;
        }

        // Check if already booked
        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $scheduleId)
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['error' => $request->name . ' sudah terdaftar di jadwal ini.']);
        }

        // Create booking
        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $userId,
            'schedule_id' => $scheduleId,
            'booking_date' => now(),
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create transaction (cash/walk-in)
        DB::table('transactions')->insert([
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'recorded_by' => Session::get('user_id'),
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'status' => 'settlement',
            'transaction_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Decrease available slots
        DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->decrement('available_slots');

        return redirect()->route('admin.schedules.view', $scheduleId)
            ->with('success', $request->name . ' berhasil ditambahkan sebagai peserta.');
    }

    public function destroy($scheduleId)
    {
        $hasBookings = DB::table('bookings')->where('schedule_id', $scheduleId)->exists();
        if ($hasBookings) {
            return redirect()->route('admin.dashboard')
                ->withErrors(['error' => 'Jadwal tidak bisa dihapus karena sudah ada booking.']);
        }

        DB::table('schedules')->where('schedule_id', $scheduleId)->delete();

        return redirect()->route('admin.dashboard')
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
