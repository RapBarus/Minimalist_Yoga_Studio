<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->select(
                'coaches.coach_id',
                'classes.class_name',
                'coaches.rate_per_class',
                'coaches.years_experience',
                'coaches.created_at',
                'users.name',
                'users.phone_number',
                'users.status'
            )
            ->orderBy('coaches.created_at', 'desc')
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        return view('admin.coaches', compact('coaches', 'classes'));
    }

    public function detail(Request $request, $coachId)
    {
        $coach = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->where('coaches.coach_id', $coachId)
            ->select(
                'coaches.coach_id',
                'coaches.user_id',
                'classes.class_name',
                'classes.class_id',
                'coaches.bio',
                'coaches.rate_per_class',
                'coaches.years_experience',
                'users.name',
                'users.phone_number',
                'users.status'
            )
            ->first();

        abort_if(!$coach, 404);

        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $classHistory = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.coach_id', $coachId)
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->orderBy('schedules.schedule_date', 'desc')
            ->select(
                'schedules.schedule_id',
                'schedules.title',
                'classes.class_name',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time'
            )
            ->get();

        $totalPendapatan = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.booking_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->where('schedules.coach_id', $coachId)
            ->whereBetween('schedules.schedule_date', [$from, $to])
            ->whereIn('transactions.status', ['settlement', 'capture'])
            ->sum('transactions.amount');

        if (DB::getSchemaBuilder()->hasTable('coach_income')) {
            $manualPendapatan = DB::table('coach_income')
                ->where('coach_id', $coachId)
                ->whereBetween('session_date', [$from, $to])
                ->sum('amount');
            $totalPendapatan += $manualPendapatan;
        }

        $allClasses = DB::table('classes')->orderBy('class_name')->get();

        return view('admin.coach_detail', compact(
            'coach',
            'classHistory',
            'totalPendapatan',
            'allClasses',
            'from',
            'to'
        ));
    }

    public function addPendapatan(Request $request, $coachId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        DB::table('coach_income')->insert([
            'coach_id' => $coachId,
            'amount' => $request->amount,
            'notes' => 'Manual entry',
            'session_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.coaches.detail', $coachId)
            ->with('success', 'Pendapatan berhasil ditambahkan.');
    }

    public function update(Request $request, $coachId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['nullable', 'string', 'regex:/^(\+?62|0)[0-9]{8,13}/'],
            'class_id' => 'required|integer|exists:classes,class_id',
            'bio' => 'nullable|string',
            'rate_per_class' => 'nullable|numeric|min:0',
        ], [
            'name.required' => 'Nama coach wajib diisi.',
            'phone.regex' => 'Format nomor HP tidak valid.',
        ]);

        $coach = DB::table('coaches')->where('coach_id', $coachId)->first();
        abort_if(!$coach, 404);

        $updateData = ['name' => $request->name, 'updated_at' => now()];

        if ($request->filled('phone')) {
            $phone = $request->phone;
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '+')) {
                $phone = '+' . $phone;
            }
            $updateData['phone_number'] = $phone;
        }

        DB::table('users')->where('user_id', $coach->user_id)->update($updateData);
        DB::table('coaches')->where('coach_id', $coachId)->update([
            'class_id' => $request->class_id,
            'bio' => $request->bio,
            'rate_per_class' => $request->rate_per_class,
            'updated_at' => now(),
        ]);

        Cache::forget('all_coaches');

        return redirect()->route('admin.coaches.detail', $coachId)
            ->with('success', 'Data coach berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'regex:/^(0|62)[0-9]{8,13}/'],
            'password' => 'required|string|min:6',
            'class_id' => 'required|integer|exists:classes,class_id',
            'rate_per_class' => 'required|numeric|min:0',
            'years_experience' => 'required|integer|min:0',
        ]);

        $baseName = strtolower(str_replace(' ', '', $request->name));
        $username = $baseName;
        $counter = 1;
        while (DB::table('users')->where('username', $username)->exists()) {
            $username = $baseName . $counter++;
        }

        $userId = DB::table('users')->insertGetId([
            'username' => $username,
            'name' => $request->name,
            'phone_number' => '+62' . ltrim($request->phone, '0'),
            'password_hash' => Hash::make($request->password),
            'role' => 'coach',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('coaches')->insert([
            'user_id' => $userId,
            'class_id' => $request->class_id,
            'bio' => $request->bio ?? null,
            'rate_per_class' => $request->rate_per_class,
            'years_experience' => $request->years_experience,
            'created_at' => now(),
        ]);

        Cache::forget('all_coaches');

        return redirect()->route('admin.coaches')
            ->with('success', 'Coach berhasil ditambahkan! Login: ' . $username . '@coach.com');
    }

    public function restore($coachId)
    {
        $coach = DB::table('coaches')->where('coach_id', $coachId)->first();
        if ($coach) {
            DB::table('users')->where('user_id', $coach->user_id)->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);
        }

        Cache::forget('all_coaches');

        return redirect()->route('admin.coaches')
            ->with('success', 'Coach berhasil diaktifkan kembali.');
    }

    public function destroy($coachId)
    {
        $coach = DB::table('coaches')->where('coach_id', $coachId)->first();
        if ($coach) {
            DB::table('users')->where('user_id', $coach->user_id)->update([
                'status' => 'inactive',
                'updated_at' => now(),
            ]);
        }

        Cache::forget('all_coaches');

        return redirect()->route('admin.coaches')
            ->with('success', 'Coach berhasil dinonaktifkan.');
    }
}
