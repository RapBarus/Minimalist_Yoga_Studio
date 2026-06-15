<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');
        $this->checkPendingPayments($userId);

        $baseQuery = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->leftJoin('transactions', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.booking_id',
                'bookings.status as booking_status',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.coach_id',
                'classes.class_name',
                'coaches.rate_per_class',
                'users.name as coach_name',
                'transactions.amount'
            );

        $activeBookings = (clone $baseQuery)
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->whereIn('bookings.status', ['confirmed'])
            ->orderBy('schedules.schedule_date', 'asc')
            ->get();

        $historyBookings = (clone $baseQuery)
            ->where(function ($q) {
                $q->where('schedules.schedule_date', '<', now()->toDateString())
                    ->orWhere('bookings.status', 'cancelled');
            })
            ->orderBy('schedules.schedule_date', 'desc')
            ->get();

        $membershipPurchases = DB::table('transactions')
            ->join('membership_quotas', 'transactions.quota_id', '=', 'membership_quotas.quota_id')
            ->join('membership_packages', 'membership_quotas.package_id', '=', 'membership_packages.package_id')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_quotas.user_id', $userId)
            ->whereIn('transactions.status', ['settlement', 'paid'])
            ->where('membership_quotas.is_active', 1)
            ->select(
                'transactions.transaction_id',
                'transactions.amount',
                'transactions.created_at',
                'transactions.xendit_external_id',
                'membership_packages.name as package_name',
                'membership_packages.validity_months',
                'classes.class_name',
                'membership_quotas.is_active',
                'membership_quotas.start_date',
                'membership_quotas.reset_date',
                'membership_quotas.total_quota',
                'membership_quotas.used_quota',
                'membership_quotas.package_id'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->get();

        return view('pages.activity', compact('activeBookings', 'historyBookings', 'membershipPurchases'));
    }

    private function checkPendingPayments($userId)
    {
        $pendingTransactions = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.user_id', $userId)
            ->where('transactions.status', 'pending')
            ->whereNotNull('transactions.xendit_external_id')
            ->select(
                'transactions.transaction_id',
                'transactions.booking_id',
                'transactions.xendit_external_id',
                'bookings.schedule_id'
            )
            ->get();

        if ($pendingTransactions->isEmpty())
            return;

        try {
            \Xendit\Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new \Xendit\Invoice\InvoiceApi();

            foreach ($pendingTransactions as $tx) {
                $invoices = $apiInstance->getInvoices(null, $tx->xendit_external_id);

                if (empty($invoices))
                    continue;

                $invoice = $invoices[0];
                $status = $invoice->getStatus();

                if ($status === 'PAID' || $status === 'SETTLED') {
                    DB::table('bookings')
                        ->where('booking_id', $tx->booking_id)
                        ->update(['status' => 'confirmed', 'updated_at' => now()]);

                    DB::table('transactions')
                        ->where('transaction_id', $tx->transaction_id)
                        ->update([
                            'status' => 'settlement',
                            'payment_type' => $invoice->getPaymentMethod() ?? 'xendit',
                            'updated_at' => now(),
                        ]);

                } elseif ($status === 'EXPIRED') {
                    DB::table('bookings')
                        ->where('booking_id', $tx->booking_id)
                        ->update(['status' => 'cancelled', 'cancellation_date' => now(), 'updated_at' => now()]);

                    DB::table('transactions')
                        ->where('transaction_id', $tx->transaction_id)
                        ->update(['status' => 'failed', 'updated_at' => now()]);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Xendit check error: ' . $e->getMessage());
        }
    }
}
