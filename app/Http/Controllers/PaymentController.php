<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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

    public function showMethod($schedule_id)
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
                'schedules.class_id',
                'classes.class_name',
                'users.name as coach_name',
                'coaches.rate_per_class as price'
            )
            ->first();

        if (!$schedule) {
            return redirect()->route('home')->withErrors(['error' => 'Jadwal tidak ditemukan.']);
        }

        $userId = Session::get('user_id');
        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('home')->withErrors(['error' => 'Anda sudah terdaftar di kelas ini.']);
        }

        $activeQuota = DB::table('membership_quotas')
            ->join('membership_packages', 'membership_quotas.package_id', '=', 'membership_packages.package_id')
            ->join('transactions', 'transactions.quota_id', '=', 'membership_quotas.quota_id')
            ->where('membership_quotas.user_id', $userId)
            ->where('membership_quotas.class_id', $schedule->class_id)
            ->where('membership_quotas.is_active', 1)
            ->where('membership_quotas.reset_date', '>=', now()->toDateString())
            ->whereRaw('membership_quotas.used_quota < membership_quotas.total_quota')
            ->whereIn('transactions.status', ['settlement', 'paid'])
            ->select('membership_quotas.*', 'membership_packages.name as package_name')
            ->first();

        return view('pages.payment-method', compact('schedule', 'activeQuota'));
    }

    public function processMethod(Request $request, $schedule_id)
    {
        $userId = Session::get('user_id');
        if (!$userId)
            return redirect()->route('login');

        $request->validate([
            'payment_method' => 'required|in:QRIS,GOPAY,OVO,DANA,SHOPEEPAY',
        ]);

        $schedule = DB::table('schedules')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.schedule_id', $schedule_id)
            ->select('schedules.*', 'coaches.rate_per_class', 'classes.class_name')
            ->first();

        if (!$schedule || $schedule->available_slots <= 0) {
            return redirect()->route('home')->withErrors('Jadwal tidak tersedia.');
        }

        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('home')->withErrors('Anda sudah terdaftar di kelas ini.');
        }

        $user = DB::table('users')->where('user_id', $userId)->first();
        $method = $request->payment_method;
        $amount = (float) max($schedule->rate_per_class, 1000);

        DB::beginTransaction();
        try {
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $userId,
                'schedule_id' => $schedule_id,
                'booking_date' => now(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $externalId = 'booking-' . $bookingId . '-' . time();

            $apiInstance = new InvoiceApi();

            $paymentMethodMap = [
                'QRIS' => 'QRIS',
                'GOPAY' => 'GOPAY',
                'OVO' => 'OVO',
                'DANA' => 'DANA',
                'SHOPEEPAY' => 'SHOPEEPAY',
            ];

            $invoiceRequest = new CreateInvoiceRequest([
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => 'Booking ' . $schedule->class_name . ' - Minimalist Studio',
                'invoice_duration' => 3600,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user->name)) . '@minimaliststudio.com',
                ],
                'success_redirect_url' => route('payment.success', $bookingId),
                'failure_redirect_url' => route('payment.failed', $bookingId),
                'currency' => 'IDR',
                'payment_methods' => [$paymentMethodMap[$method]],
            ]);

            $invoice = $apiInstance->createInvoice($invoiceRequest);

            DB::table('transactions')->insert([
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'amount' => $schedule->rate_per_class,
                'payment_type' => 'ewallet',
                'payment_channel' => $method,
                'xendit_external_id' => $externalId,
                'xendit_invoice_url' => $invoice->getInvoiceUrl(),
                'status' => 'pending',
                'expiry_time' => now()->addHour(),
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect($invoice->getInvoiceUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment error: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function instructions($transactionId)
    {
        $transaction = DB::table('transactions')->where('transaction_id', $transactionId)->first();
        if (!$transaction)
            abort(404);

        $booking = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('bookings.booking_id', $transaction->booking_id)
            ->select('bookings.*', 'classes.class_name')
            ->first();

        $schedule = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.schedule_id', $booking->schedule_id)
            ->select('schedules.*', 'classes.class_name', 'users.name as coach_name', 'coaches.rate_per_class as price')
            ->first();

        $qrCode = Session::get('payment_qr');
        $deeplink = Session::get('payment_deeplink');
        $expiryTime = Session::get('payment_expiry');

        return view('pages.payment-instructions', compact(
            'transaction',
            'booking',
            'schedule',
            'qrCode',
            'deeplink',
            'expiryTime'
        ));
    }

    public function check($transactionId)
    {
        $transaction = DB::table('transactions')->where('transaction_id', $transactionId)->first();
        if (!$transaction)
            abort(404);

        try {
            $ewalletApi = new EWalletApi();
            $charge = $ewalletApi->getEWalletChargeStatus($transaction->xendit_id);
            $status = $charge->getStatus();

            if ($status === 'SUCCEEDED') {
                DB::table('bookings')
                    ->where('booking_id', $transaction->booking_id)
                    ->update(['status' => 'confirmed', 'updated_at' => now()]);

                DB::table('transactions')
                    ->where('transaction_id', $transactionId)
                    ->update(['status' => 'settlement', 'updated_at' => now()]);

                Cache::forget('schedules_week');

                return redirect()->route('activity')
                    ->with('success', 'Pembayaran berhasil! Anda telah terdaftar di kelas.');
            } elseif ($status === 'FAILED' || $status === 'VOIDED') {
                return redirect()->route('payment.instructions', $transactionId)
                    ->withErrors('Pembayaran gagal. Silakan coba lagi.');
            } else {
                return redirect()->route('payment.instructions', $transactionId)
                    ->with('info', 'Pembayaran belum selesai. Silakan selesaikan pembayaran.');
            }
        } catch (\Exception $e) {
            Log::error('Check payment error: ' . $e->getMessage());
            return redirect()->route('payment.instructions', $transactionId)
                ->withErrors('Tidak dapat mengecek status. Coba lagi.');
        }
    }

    public function cancel($bookingId)
    {
        $userId = Session::get('user_id');

        DB::table('bookings')
            ->where('booking_id', $bookingId)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'cancellation_date' => now(), 'updated_at' => now()]);

        DB::table('transactions')
            ->where('booking_id', $bookingId)
            ->update(['status' => 'failed', 'updated_at' => now()]);

        Cache::forget('schedules_week');

        return redirect()->route('home')->with('info', 'Pembayaran dibatalkan.');
    }

    public function process(Request $request)
    {
        return $this->processMethod($request, $request->schedule_id);
    }

    public function success(Request $request, $bookingId)
    {
        \Log::info('Payment success hit', ['booking_id' => $bookingId]);

        $booking = DB::table('bookings')->where('booking_id', $bookingId)->first();

        if ($booking && $booking->status === 'pending') {
            $updated = DB::table('bookings')
                ->where('booking_id', $bookingId)
                ->where('status', 'pending')
                ->update(['status' => 'confirmed', 'updated_at' => now()]);

            if ($updated) {
                DB::table('transactions')
                    ->where('booking_id', $bookingId)
                    ->update(['status' => 'settlement', 'updated_at' => now()]);
            }
        }

        Cache::forget('schedules_week');

        return redirect()->route('payment.receipt', $bookingId);
    }

    public function failed(Request $request, $bookingId)
    {
        DB::table('bookings')
            ->where('booking_id', $bookingId)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'cancellation_date' => now(), 'updated_at' => now()]);

        DB::table('transactions')
            ->where('booking_id', $bookingId)
            ->update(['status' => 'failed', 'updated_at' => now()]);

        $scheduleId = DB::table('bookings')->where('booking_id', $bookingId)->value('schedule_id');
        return redirect()->route('payment.show', $scheduleId)
            ->withErrors(['error' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.']);
    }

    public function webhook(Request $request)
    {
        $webhookToken = $request->header('x-callback-token');
        if ($webhookToken !== config('services.xendit.webhook_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $status = $data['status'] ?? null;
        $externalId = $data['external_id'] ?? $data['reference_id'] ?? null;

        if (!$externalId) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        preg_match('/booking-(\d+)-/', $externalId, $matches);
        $bookingId = $matches[1] ?? null;

        if (!$bookingId) {
            return response()->json(['error' => 'Invalid external_id'], 400);
        }

        if (in_array($status, ['PAID', 'SETTLED', 'SUCCEEDED'])) {
            $booking = DB::table('bookings')->where('booking_id', $bookingId)->first();

            if ($booking && $booking->status === 'pending') {
                DB::table('bookings')
                    ->where('booking_id', $bookingId)
                    ->update(['status' => 'confirmed', 'updated_at' => now()]);

                DB::table('transactions')
                    ->where('booking_id', $bookingId)
                    ->update(['status' => 'settlement', 'updated_at' => now()]);

                Cache::forget('schedules_week');
            }
        } elseif (in_array($status, ['EXPIRED', 'FAILED', 'VOIDED'])) {
            DB::table('bookings')
                ->where('booking_id', $bookingId)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled', 'cancellation_date' => now(), 'updated_at' => now()]);

            DB::table('transactions')
                ->where('booking_id', $bookingId)
                ->update(['status' => 'failed', 'updated_at' => now()]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function receipt($bookingId)
    {
        $userId = Session::get('user_id');
        $booking = DB::table('bookings')->where('booking_id', $bookingId)->where('user_id', $userId)->first();
        if (!$booking)
            abort(404);

        $transaction = DB::table('transactions')->where('booking_id', $bookingId)->first();
        $schedule = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.schedule_id', $booking->schedule_id)
            ->select('schedules.*', 'classes.class_name', 'users.name as coach_name')
            ->first();
        $user = DB::table('users')->where('user_id', $userId)->first();

        return view('pages.payment-receipt', compact('transaction', 'schedule', 'user'));
    }

    public function useQuota(Request $request)
    {
        $userId = Session::get('user_id');
        $scheduleId = $request->quota_id ? $request->schedule_id : null;
        $quotaId = $request->quota_id;

        if (!$userId || !$scheduleId || !$quotaId)
            return redirect()->route('home')->withErrors('Data tidak valid.');

        $quota = DB::table('membership_quotas')
            ->join('transactions', 'membership_quotas.quota_id', '=', 'transactions.quota_id')
            ->where('membership_quotas.user_id', $userId)
            ->where('membership_quotas.quota_id', $quotaId)
            ->where('membership_quotas.is_active', 1)
            ->whereRaw('membership_quotas.used_quota < membership_quotas.total_quota')
            ->select('membership_quotas.*')
            ->first();

        if (!$quota)
            return redirect()->route('home')->withErrors('Kuota tidak valid atau sudah habis.');

        $alreadyBooked = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('schedule_id', $scheduleId)
            ->exists();

        if ($alreadyBooked)
            return redirect()->route('home')->withErrors('Anda sudah terdaftar di kelas ini.');

        DB::beginTransaction();
        try {
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $userId,
                'schedule_id' => $scheduleId,
                'booking_date' => now(),
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('transactions')->insert([
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'amount' => 0,
                'payment_type' => 'membership_quota',
                'payment_channel' => 'quota',
                'status' => 'settlement',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('membership_quotas')
                ->where('quota_id', $quotaId)
                ->increment('used_quota');

            DB::commit();

            Cache::forget('schedules_week');

            return redirect()->route('activity')->with('success', 'Berhasil mendaftar menggunakan kuota membership!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Use quota error: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
