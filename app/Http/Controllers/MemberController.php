<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class MemberController extends Controller
{
    public function index()
    {
        $promotions = Cache::remember('all_packages', 3600, function () {
            return DB::table('membership_packages')
                ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
                ->where('membership_packages.is_active', 1)
                ->orderBy('membership_packages.package_id', 'asc')
                ->select(
                    'membership_packages.*',
                    'classes.class_name'
                )
                ->get()
                ->map(function ($package) {
                    $package->title = $package->name;
                    $package->promo_price = number_format($package->price, 0, ',', '.');
                    $package->pertemuan = $package->quota_amount . 'x sesi';
                    $package->masa_aktif = $package->validity_months * 30 . ' Hari';
                    $package->promo_id = $package->package_id;
                    return $package;
                });
        });

        return view('pages.member', compact('promotions'));
    }
}
