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

        $bookings = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('bookings.user_id', $userId)
            ->orderBy('schedules.schedule_date', 'desc')
            ->select(
                'classes.class_name',
                'schedules.schedule_date',
                'bookings.status'
            )
            ->get();

        return view('admin.customer_detail', compact('customer', 'bookings'));
    }
}
