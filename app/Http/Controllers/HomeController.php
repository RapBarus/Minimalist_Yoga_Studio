<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');
        $this->cleanupExpiredBookings($userId);

        // Cache raw schedules for 5 minutes (shared across all users)
        $rawSchedules = Cache::remember('schedules_week', 300, function () {
            return DB::table('schedules')
                ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
                ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
                ->join('users', 'coaches.user_id', '=', 'users.user_id')
                ->whereIn('schedules.status', ['upcoming', 'ongoing'])
                ->whereBetween('schedules.schedule_date', [
                    now()->toDateString(),
                    now()->addDays(6)->toDateString()
                ])
                // Exclude kelas hari ini yang end_time-nya sudah lewat
                ->where(function ($query) {
                    $nowJkt = now('Asia/Jakarta');
                    $query->where('schedules.schedule_date', '>', $nowJkt->toDateString())
                        ->orWhere('schedules.end_time', '>', $nowJkt->format('H:i:s'));
                })
                ->orderBy('schedules.schedule_date', 'asc')
                ->orderBy('schedules.start_time', 'asc')
                ->select(
                    'schedules.schedule_id',
                    'schedules.schedule_date',
                    'schedules.start_time',
                    'schedules.end_time',
                    'schedules.available_slots',
                    'schedules.status',
                    'schedules.coach_id',
                    'schedules.title',
                    'classes.class_name',
                    'coaches.rate_per_class',
                    'coaches.profile_photo',
                    'users.name as coach_name'
                )
                ->get();
        });

        // Only confirmed/attended count as "already booked" — pending does not block rebooking
        $bookedScheduleIds = DB::table('bookings')
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'attended'])
            ->pluck('schedule_id')
            ->toArray();

        // Mark each schedule as booked
        $schedules = collect($rawSchedules)->map(function ($schedule) use ($bookedScheduleIds) {
            $schedule->already_booked = in_array($schedule->schedule_id, $bookedScheduleIds);
            return $schedule;
        });

        // Sort: available first, full second, already-booked last
        $schedules = $schedules->sortBy(function ($s) {
            if ($s->already_booked)
                return 2;
            if ($s->available_slots <= 0)
                return 1;
            return 0;
        })->values();

        // Today's schedules only (for default view)
        $todaySchedules = $schedules->filter(function ($s) {
            return $s->schedule_date === now()->toDateString();
        })->values();

        // Cache all classes for 1 hour
        $allClasses = Cache::remember('all_classes', 3600, function () {
            return DB::table('classes')
                ->orderBy('class_name', 'asc')
                ->pluck('class_name');
        });

        // Cache all active coaches for 1 hour
        $allCoaches = Cache::remember('all_coaches', 3600, function () {
            return DB::table('coaches')
                ->join('users', 'coaches.user_id', '=', 'users.user_id')
                ->where('users.status', 'active')
                ->where('users.role', 'coach')
                ->orderBy('users.name', 'asc')
                ->pluck('users.name');
        });

        // Cache promotions for 1 hour
        $promotions = Cache::remember('promotions_home', 3600, function () {
            return DB::table('membership_packages')
                ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
                ->where('membership_packages.is_active', 1)
                ->orderBy('membership_packages.package_id', 'asc')
                ->take(6)
                ->select('membership_packages.*', 'classes.class_name')
                ->get()
                ->map(function ($package) {
                    $package->title = $package->name;
                    $package->coach_name = null;
                    $package->coach_id = null;
                    $package->schedule_date = null;
                    $package->start_time = null;
                    $package->end_time = null;
                    $package->promo_price = number_format($package->price, 0, ',', '.');
                    $package->masa_aktif = $package->validity_months * 30 . ' Hari';
                    $package->promo_id = $package->package_id;
                    return $package;
                });
        });

        return view('pages.home', [
            'schedules' => $schedules,
            'todaySchedules' => $todaySchedules,
            'promotions' => $promotions,
            'allClasses' => $allClasses,
            'allCoaches' => $allCoaches,
            'user_name' => Session::get('user_name', 'Member'),
        ]);
    }

    private function cleanupExpiredBookings($userId)
    {
        $expired = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->where('bookings.user_id', $userId)
            ->where('bookings.status', 'pending')
            ->where('transactions.status', 'pending')
            ->where('transactions.expiry_time', '<', now())
            ->select('transactions.transaction_id', 'bookings.booking_id')
            ->get();

        foreach ($expired as $row) {
            DB::table('bookings')
                ->where('booking_id', $row->booking_id)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled', 'cancellation_date' => now(), 'updated_at' => now()]);

            DB::table('transactions')
                ->where('transaction_id', $row->transaction_id)
                ->where('status', 'pending')
                ->update(['status' => 'failed', 'updated_at' => now()]);
        }

        if ($expired->isNotEmpty()) {
            Cache::forget('schedules_week');
        }
    }

    public function coachProfile($coachId)
    {
        if (!is_numeric($coachId)) {
            abort(404);
        }

        $coach = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->where('coaches.coach_id', $coachId)
            ->select(
                'coaches.coach_id',
                'classes.class_name as specialization',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->first();
        abort_if(!$coach, 404);

        $coachSchedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.coach_id', $coachId)
            ->where('schedules.status', 'upcoming')
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->where(function ($query) {
                $nowJkt = now('Asia/Jakarta');
                $query->where('schedules.schedule_date', '>', $nowJkt->toDateString())
                    ->orWhere('schedules.end_time', '>', $nowJkt->format('H:i:s'));
            })
            ->orderBy('schedules.schedule_date', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.available_slots',
                DB::raw('COALESCE(schedules.title, classes.class_name) as class_name')
            )
            ->get();

        return view('pages.coach_profile', compact('coach', 'coachSchedules'));
    }
}
