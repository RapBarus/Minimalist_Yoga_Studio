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

    // app/Http/Controllers/PaymentController.php

    public function process(Request $request)
    {
        $userId = Session::get('user_id');

        // Validate that user is logged in
        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'schedule_id'   => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        $schedule = DB::table('schedules')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->where('schedules.schedule_id', $request->schedule_id)
            ->select('schedules.*', 'coaches.rate_per_class')
            ->first();

        if (!$schedule) {
            return redirect()->route('home')->withErrors('Jadwal tidak ditemukan.');
        }

        // Check availability (triggers also protect, but user-friendly feedback)
        if ($schedule->available_slots <= 0) {
            return redirect()->route('home')->withErrors('Maaf, kelas sudah penuh.');
        }

        // Check if user already booked this schedule
        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('home')->withErrors('Anda sudah terdaftar di kelas ini.');
        }

        // All good – create booking & transaction inside a database transaction
        DB::beginTransaction();
        try {
            // 1. Create booking
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id'       => $userId,
                'schedule_id'   => $request->schedule_id,
                'booking_date'  => now(),
                'status'        => 'confirmed',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 2. Create transaction (simulated payment success)
            DB::table('transactions')->insert([
                'user_id'         => $userId,
                'booking_id'      => $bookingId,
                'recorded_by'     => null,                // customer self-service
                'amount'          => $schedule->rate_per_class,
                'payment_type'    => $request->payment_method,
                'payment_channel' => $request->payment_method,  // e.g. gopay, qris
                'status'          => 'settlement',        // simulated success
                'transaction_date' => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->withErrors('Terjadi kesalahan saat memproses pembayaran.');
        }

        return redirect()->route('home')->with('success', 'Pembayaran berhasil! Anda telah terdaftar di kelas.');
    }
}
