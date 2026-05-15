<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class MembershipPaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    // ── Show membership payment confirmation ──
    public function show($package_id)
    {
        $package = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_packages.package_id', $package_id)
            ->where('membership_packages.is_active', 1)
            ->select('membership_packages.*', 'classes.class_name')
            ->first();

        if (!$package) {
            return redirect()->route('member')->withErrors('Paket tidak ditemukan.');
        }

        return view('pages.membership-payment', compact('package'));
    }

    // ── Show payment method selection ──
    public function showMethod($package_id)
    {
        $package = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_packages.package_id', $package_id)
            ->where('membership_packages.is_active', 1)
            ->select('membership_packages.*', 'classes.class_name')
            ->first();

        if (!$package) {
            return redirect()->route('member')->withErrors('Paket tidak ditemukan.');
        }

        return view('pages.membership-payment-method', compact('package'));
    }

    // ── Process payment ──
    public function processMethod(Request $request, $package_id)
    {
        $userId = Session::get('user_id');
        if (!$userId)
            return redirect()->route('login');

        $request->validate([
            'payment_method' => 'required|in:QRIS,GOPAY,OVO,DANA,SHOPEEPAY',
        ]);

        $package = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_packages.package_id', $package_id)
            ->where('membership_packages.is_active', 1)
            ->select('membership_packages.*', 'classes.class_name')
            ->first();

        if (!$package) {
            return redirect()->route('member')->withErrors('Paket tidak ditemukan.');
        }

        $user = DB::table('users')->where('user_id', $userId)->first();
        $method = $request->payment_method;
        $amount = (float) max($package->price, 1000);

        DB::beginTransaction();
        try {
            $externalId = 'membership-' . $package_id . '-' . $userId . '-' . time();

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
                'description' => 'Membership ' . $package->name . ' - Minimalist Studio',
                'invoice_duration' => 3600,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user->name)) . '@minimaliststudio.com',
                ],
                'success_redirect_url' => route('membership.payment.success') . '?external_id=' . $externalId . '&package_id=' . $package_id,
                'failure_redirect_url' => route('membership.payment.failed', $package_id),
                'currency' => 'IDR',
                'payment_methods' => [$paymentMethodMap[$method]],
            ]);

            $invoice = $apiInstance->createInvoice($invoiceRequest);

            // 1. Create inactive quota first to get quota_id
            $quotaId = DB::table('membership_quotas')->insertGetId([
                'user_id' => $userId,
                'package_id' => $package_id,
                'class_id' => $package->class_id,
                'total_quota' => $package->quota_amount,
                'used_quota' => 0,
                'start_date' => now()->toDateString(),
                'reset_date' => now()->addDays($package->validity_months * 30)->toDateString(),
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Insert transaction with quota_id (satisfies XOR constraint)
            DB::table('transactions')->insert([
                'user_id' => $userId,
                'booking_id' => null,
                'quota_id' => $quotaId,
                'amount' => $package->price,
                'payment_type' => 'membership',
                'payment_channel' => $method,
                'xendit_external_id' => $externalId,
                'xendit_invoice_url' => $invoice->getInvoiceUrl(),
                'status' => 'pending',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect($invoice->getInvoiceUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Membership payment error: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── Handle success redirect from Xendit ──
    public function success(Request $request)
    {
        \Log::info('Membership success hit', $request->all());
        \Log::info('Session user_id: ' . Session::get('user_id'));
        $externalId = $request->get('external_id');
        $packageId = $request->get('package_id');
        $userId = Session::get('user_id');

        if (!$userId && $externalId) {
            preg_match('/membership-\d+-(\d+)-/', $externalId, $matches);
            $userId = $matches[1] ?? null;
        }


        if (!$externalId || !$packageId || !$userId) {
            return redirect()->route('member')->withErrors('Data pembayaran tidak valid.');
        }

        DB::table('membership_quotas')
            ->where('user_id', $userId)
            ->where('package_id', $packageId)
            ->where('is_active', 0)
            ->update(['is_active' => 1, 'updated_at' => now()]);

        DB::table('transactions')
            ->where('xendit_external_id', $externalId)
            ->update(['status' => 'settlement', 'updated_at' => now()]);

        $transaction = DB::table('transactions')->where('xendit_external_id', $externalId)->first();
        $package = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->where('membership_packages.package_id', $packageId)
            ->select('membership_packages.*', 'classes.class_name')
            ->first();
        $user = DB::table('users')->where('user_id', $userId)->first();

        return view('pages.membership-receipt', compact('transaction', 'package', 'user'));
    }

    // ── Handle failed payment ──
    public function failed($package_id)
    {
        return redirect()->route('membership.payment.show', $package_id)
            ->withErrors('Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }

    // ── Webhook ──
    public function webhook(Request $request)
    {
        $webhookToken = $request->header('x-callback-token');
        if ($webhookToken !== config('services.xendit.webhook_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $status = $data['status'] ?? null;
        $externalId = $data['external_id'] ?? null;

        if (!$externalId || !str_starts_with($externalId, 'membership-')) {
            return response()->json(['status' => 'ignored']);
        }

        // Extract package_id and user_id from external_id: membership-{package_id}-{user_id}-{time}
        preg_match('/membership-(\d+)-(\d+)-/', $externalId, $matches);
        $packageId = $matches[1] ?? null;
        $userId = $matches[2] ?? null;

        if (!$packageId || !$userId) {
            return response()->json(['error' => 'Invalid external_id'], 400);
        }

        if (in_array($status, ['PAID', 'SETTLED'])) {
            DB::table('membership_quotas')
                ->where('user_id', $userId)
                ->where('package_id', $packageId)
                ->where('is_active', 0)
                ->update(['is_active' => 1, 'updated_at' => now()]);

            DB::table('transactions')
                ->where('xendit_external_id', $externalId)
                ->update(['status' => 'settlement', 'updated_at' => now()]);
        }

        return response()->json(['status' => 'ok']);
    }
}
