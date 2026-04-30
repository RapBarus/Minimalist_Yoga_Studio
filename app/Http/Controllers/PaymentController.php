<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

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

        // Check if already booked
        $userId = Session::get('user_id');
        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('home')->withErrors(['error' => 'Anda sudah terdaftar di kelas ini.']);
        }

        return view('pages.payment', compact('schedule'));
    }

    public function process(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'schedule_id' => 'required|integer',
        ]);

        $schedule = DB::table('schedules')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.schedule_id', $request->schedule_id)
            ->select(
                'schedules.*',
                'coaches.rate_per_class',
                'classes.class_name'
            )
            ->first();

        if (!$schedule) {
            return redirect()->route('home')->withErrors('Jadwal tidak ditemukan.');
        }

        if ($schedule->available_slots <= 0) {
            return redirect()->route('home')->withErrors('Maaf, kelas sudah penuh.');
        }

        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('home')->withErrors('Anda sudah terdaftar di kelas ini.');
        }

        // Get user info for Xendit
        $user = DB::table('users')->where('user_id', $userId)->first();

        // Create booking first with pending status
        DB::beginTransaction();
        try {
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $userId,
                'schedule_id' => $request->schedule_id,
                'booking_date' => now(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Xendit Invoice
            $apiInstance = new InvoiceApi();
            $externalId = 'booking-' . $bookingId . '-' . time();

            $invoiceRequest = new CreateInvoiceRequest([
                'external_id' => 'booking-' . $bookingId . '-' . time(),
                'amount' => (float) max($schedule->rate_per_class, 1000),
                'description' => 'Booking ' . $schedule->class_name . ' - Minimalist Studio',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email ?? $user->name . '@minimaliststudio.com',
                ],
                'success_redirect_url' => route('payment.success', $bookingId),
                'failure_redirect_url' => route('payment.failed', $bookingId),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => $schedule->class_name,
                        'quantity' => 1,
                        'price' => (float) max($schedule->rate_per_class, 1000),
                        'category' => 'Kelas Yoga',
                    ]
                ],
            ]);

            $invoice = $apiInstance->createInvoice($invoiceRequest);

            // Store transaction with xendit invoice id
            DB::table('transactions')->insert([
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'recorded_by' => null,
                'amount' => $schedule->rate_per_class,
                'payment_type' => 'xendit',
                'payment_channel' => null,
                'xendit_external_id' => $externalId,
                'xendit_invoice_url' => $invoice->getInvoiceUrl(),
                'status' => 'pending',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Redirect to Xendit payment page
            return redirect($invoice->getInvoiceUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit payment error: ' . $e->getMessage());
            return redirect()->route('home')->withErrors('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function success(Request $request, $bookingId)
    {
        $booking = DB::table('bookings')->where('booking_id', $bookingId)->first();

        if ($booking && $booking->status === 'pending') {
            // Update booking & transaction to confirmed
            DB::table('bookings')
                ->where('booking_id', $bookingId)
                ->update(['status' => 'confirmed', 'updated_at' => now()]);

            DB::table('transactions')
                ->where('booking_id', $bookingId)
                ->update(['status' => 'settlement', 'updated_at' => now()]);

            // Decrease available slots
            DB::table('schedules')
                ->where('schedule_id', $booking->schedule_id)
                ->decrement('available_slots');
        }

        return redirect()->route('activity')
            ->with('success', 'Pembayaran sedang diproses. Status akan diperbarui otomatis.');
    }

    public function failed(Request $request, $bookingId)
    {
        // Cancel the pending booking
        DB::table('bookings')
            ->where('booking_id', $bookingId)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'updated_at' => now()]);

        DB::table('transactions')
            ->where('booking_id', $bookingId)
            ->update(['status' => 'failed', 'updated_at' => now()]);

        return redirect()->route('payment.show', DB::table('bookings')->where('booking_id', $bookingId)->value('schedule_id'))
            ->withErrors(['error' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.']);
    }

    public function webhook(Request $request)
    {
        // Verify webhook token
        $webhookToken = $request->header('x-callback-token');
        if ($webhookToken !== config('services.xendit.webhook_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $status = $data['status'] ?? null;
        $externalId = $data['external_id'] ?? null;

        if (!$externalId) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Extract booking ID from external_id (format: booking-{id}-{timestamp})
        preg_match('/booking-(\d+)-/', $externalId, $matches);
        $bookingId = $matches[1] ?? null;

        if (!$bookingId) {
            return response()->json(['error' => 'Invalid external_id'], 400);
        }

        if ($status === 'PAID' || $status === 'SETTLED') {
            $booking = DB::table('bookings')->where('booking_id', $bookingId)->first();

            if ($booking && $booking->status === 'pending') {
                DB::table('bookings')
                    ->where('booking_id', $bookingId)
                    ->update(['status' => 'confirmed', 'updated_at' => now()]);

                DB::table('transactions')
                    ->where('booking_id', $bookingId)
                    ->update([
                        'status' => 'settlement',
                        'updated_at' => now(),
                    ]);

                DB::table('schedules')
                    ->where('schedule_id', $booking->schedule_id)
                    ->decrement('available_slots');
            }
        } elseif ($status === 'EXPIRED') {
            DB::table('bookings')
                ->where('booking_id', $bookingId)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled', 'updated_at' => now()]);

            DB::table('transactions')
                ->where('booking_id', $bookingId)
                ->update([
                    'status' => 'settlement',
                    'payment_type' => $data['payment_method'] ?? 'xendit',
                    'payment_channel' => $data['payment_channel'] ?? null,
                    'xendit_id' => $data['id'] ?? null,
                    'updated_at' => now(),
                ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
