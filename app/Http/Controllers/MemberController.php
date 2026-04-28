<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function index()
    {
        $promotions = DB::table('membership_packages')
            ->where('is_active', 1)
            ->orderBy('package_id', 'asc')
            ->get()
            ->map(function ($package) {
                $package->title = $package->name;
                $package->coach_name = $package->coach_name ?? null;
                $package->coach_id = $package->coach_id ?? null;
                $package->schedule_date = $package->schedule_date ?? null;
                $package->start_time = $package->start_time ?? null;
                $package->end_time = $package->end_time ?? null;
                $package->original_price = null;
                $package->promo_price = number_format($package->price, 0, ',', '.');
                $package->pertemuan = $package->quota_amount . 'x sesi';
                $package->promo_id = $package->package_id;
                return $package;
            });

        return view('pages.member', compact('promotions'));
    }
}
