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

        $baseQuery = DB::table('vw_customer_booking_history')
            ->where('user_id', $userId)
            ->select(
                'booking_id',
                'booking_status',
                'schedule_date',
                'start_time',
                'end_time',
                'class_name',
                'coach_id',
                'rate_per_class',
                'coach_name',
                'amount'
            );

        $activeBookings = (clone $baseQuery)
            ->where('schedule_date', '>=', now()->toDateString())
            ->whereIn('booking_status', ['confirmed'])
            ->orderBy('schedule_date', 'asc')
            ->get();

        $historyBookings = (clone $baseQuery)
            ->where(function ($q) {
                $q->where('schedule_date', '<', now()->toDateString())
                    ->orWhere('booking_status', 'cancelled');
            })
            ->orderBy('schedule_date', 'desc')
            ->get();

        $membershipPurchases = DB::table('transactions')
            ->join('membership_quotas', 'transactions.quota_id', '=', 'membership_quotas.quota_id')
            ->join('membership_packages', 'membership_quotas.package_id', '=', 'membership_packages.package_id')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_quotas.user_id', $userId)
            ->whereIn('transactions.status', ['settlement', 'paid'])
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
        // Get all pending transactions for this user that have a xendit_external_id
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
                // Get invoice status from Xendit
                $invoices = $apiInstance->getInvoices(
                    null,
                    $tx->xendit_external_id
                );

                if (empty($invoices))
                    continue;

                $invoice = $invoices[0];
                $status = $invoice->getStatus();

                if ($status === 'PAID' || $status === 'SETTLED') {
                    // Confirm booking
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

                    DB::table('schedules')
                        ->where('schedule_id', $tx->schedule_id)
                        ->decrement('available_slots');

                } elseif ($status === 'EXPIRED') {
                    DB::table('bookings')
                        ->where('booking_id', $tx->booking_id)
                        ->update(['status' => 'cancelled', 'updated_at' => now()]);

                    DB::table('transactions')
                        ->where('transaction_id', $tx->transaction_id)
                        ->update(['status' => 'failed', 'updated_at' => now()]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail — don't break the page if Xendit is unreachable
            \Illuminate\Support\Facades\Log::error('Xendit check error: ' . $e->getMessage());
        }
    }
}
