<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function index()
    {
        $promotions = DB::table('promotions')
            ->where('is_active', 1)
            ->orderBy('promo_id', 'asc')
            ->get()
            ->map(function ($promo) {
                // Resolve coach_id from coach_name
                $coach = DB::table('coaches')
                    ->join('users', 'coaches.user_id', '=', 'users.user_id')
                    ->where('users.name', $promo->coach_name)
                    ->select('coaches.coach_id')
                    ->first();
                $promo->coach_id = $coach ? $coach->coach_id : null;
                return $promo;
            });

        return view('pages.member', compact('promotions'));
    }
}
