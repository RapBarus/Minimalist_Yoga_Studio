<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->select(
                'coaches.coach_id',
                'coaches.specialization',
                'coaches.rate_per_class',
                'coaches.years_experience',
                'coaches.created_at',
                'users.name',
                'users.phone_number',
                'users.status'
            )
            ->orderBy('coaches.created_at', 'desc')
            ->get();

        return view('admin.coaches', ['coaches' => $coaches]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'regex:/^[0-9]{8,13}$/'],
            'password' => 'required|string|min:6',
            'specialization' => 'required|string|max:100',
            'rate_per_class' => 'required|numeric|min:0',
            'years_experience' => 'required|integer|min:0',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Nomor HP hanya boleh angka, 8–13 digit (tanpa awalan 0).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'specialization.required' => 'Spesialisasi wajib diisi.',
            'rate_per_class.required' => 'Tarif per kelas wajib diisi.',
            'years_experience.required' => 'Pengalaman wajib diisi.',
        ]);

        $exists = DB::table('users')->where('name', $request->name)->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Username sudah digunakan.'])->withInput();
        }

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'phone_number' => '+62' . ltrim($request->phone, '0'),
            'email' => null,
            'password_hash' => Hash::make($request->password),
            'role' => 'coach',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('coaches')->insert([
            'user_id' => $userId,
            'specialization' => $request->specialization,
            'rate_per_class' => $request->rate_per_class,
            'years_experience' => $request->years_experience,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.coaches')
            ->with('success', 'Coach ' . $request->name . ' berhasil ditambahkan! Login dengan ' . $request->name . '@coach.com');
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

        return redirect()->route('admin.coaches')->with('success', 'Coach berhasil diaktifkan kembali.');
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

        return redirect()->route('admin.coaches')->with('success', 'Coach berhasil dinonaktifkan.');
    }
}
