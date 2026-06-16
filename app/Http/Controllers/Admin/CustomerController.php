<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::table('users')
            ->where('role', 'customer')
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->select('user_id', 'name', 'phone_number', 'created_at')
            ->get();

        return view('admin.customers', compact('customers'));
    }

    public function detail($userId)
    {
        $customer = DB::table('users')
            ->where('user_id', $userId)
            ->where('role', 'customer')
            ->select('user_id', 'name', 'phone_number', 'created_at', 'status')
            ->first();

        abort_if(!$customer, 404);

        $memberships = DB::table('membership_quotas')
            ->join('membership_packages', 'membership_quotas.package_id', '=', 'membership_packages.package_id')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->join('transactions', 'transactions.quota_id', '=', 'membership_quotas.quota_id')
            ->where('membership_quotas.user_id', $userId)
            ->where('membership_quotas.is_active', 1)
            ->whereIn('transactions.status', ['settlement', 'paid'])
            ->select(
                'membership_quotas.quota_id',
                'membership_quotas.used_quota',
                'membership_quotas.total_quota',
                'membership_quotas.start_date',
                'membership_quotas.reset_date',
                'membership_packages.name as package_name',
                'membership_packages.price',
                'membership_packages.original_price',
                'classes.class_name'
            )
            ->get();

        $activeBookings = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users as coach_users', 'coaches.user_id', '=', 'coach_users.user_id')
            ->leftJoin('transactions', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'attended'])
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->select(
                'bookings.booking_id',
                DB::raw('COALESCE(schedules.title, classes.class_name) as class_name'),
                'coach_users.name as coach_name',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'transactions.amount'
            )
            ->get();

        return view('admin.customer_detail', compact('customer', 'memberships', 'activeBookings'));
    }

    public function stopMembership($userId, $quotaId)
    {
        DB::table('membership_quotas')
            ->where('quota_id', $quotaId)
            ->where('user_id', $userId)
            ->update(['is_active' => 0, 'updated_at' => now()]);

        return redirect()->route('admin.customers.detail', $userId)
            ->with('success', 'Membership berhasil dihentikan.');
    }

    public function cancelBooking($userId, $bookingId)
    {
        $booking = DB::table('bookings')
            ->where('booking_id', $bookingId)
            ->where('user_id', $userId)
            ->first();

        if ($booking) {
            if ($booking->group_id) {
                $affectedBookingIds = DB::table('bookings')
                    ->where('group_id', $booking->group_id)
                    ->whereIn('status', ['confirmed', 'pending', 'attended'])
                    ->pluck('booking_id');

                DB::table('bookings')
                    ->where('group_id', $booking->group_id)
                    ->whereIn('status', ['confirmed', 'pending', 'attended'])
                    ->update([
                        'status' => 'cancelled',
                        'cancellation_date' => now(),
                        'updated_at' => now(),
                    ]);
            } else {
                $affectedBookingIds = [$bookingId];

                DB::table('bookings')
                    ->where('booking_id', $bookingId)
                    ->update([
                        'status' => 'cancelled',
                        'cancellation_date' => now(),
                        'updated_at' => now(),
                    ]);
            }

            DB::table('transactions')
                ->whereIn('booking_id', $affectedBookingIds)
                ->whereIn('status', ['settlement', 'capture', 'paid'])
                ->update([
                    'status' => 'failed',
                    'updated_at' => now(),
                ]);

            \Illuminate\Support\Facades\Cache::forget('schedules_week');
        }

        return redirect()->route('admin.customers.detail', $userId)
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
