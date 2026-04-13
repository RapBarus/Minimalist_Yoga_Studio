<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        // Total kelas (schedules with at least 1 booking in period)
        $totalKelas = DB::table('schedules')
            ->whereBetween('schedule_date', [$from, $to])
            ->count();

        // Total peserta (confirmed/attended bookings in period)
        $totalPeserta = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->whereIn('bookings.status', ['confirmed', 'attended'])
            ->count();

        // Total pendapatan
        $totalPendapatan = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->whereIn('transactions.status', ['settlement', 'capture'])
            ->sum('transactions.amount');

        // Chart data: pendapatan per class
        $chartRows = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->whereIn('transactions.status', ['settlement', 'capture'])
            ->groupBy('classes.class_name')
            ->select(
                'classes.class_name',
                DB::raw('COUNT(bookings.booking_id) as peserta'),
                DB::raw('SUM(transactions.amount) as pendapatan')
            )
            ->get();

        $chartLabels = $chartRows->pluck('class_name')->toArray();
        $chartData = $chartRows->pluck('pendapatan')->map(fn($v) => (float) $v)->toArray();
        $tableRows = $chartRows->toArray();

        return view('admin.keuangan', compact(
            'from',
            'to',
            'totalKelas',
            'totalPeserta',
            'totalPendapatan',
            'chartLabels',
            'chartData',
            'tableRows'
        ));
    }
}
