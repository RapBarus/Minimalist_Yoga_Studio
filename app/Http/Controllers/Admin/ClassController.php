<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('classes')->orderBy('created_at', 'desc')->get();

        return view('admin.classes', ['classes' => $classes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_minutes' => 'required|integer|min:1',
        ], [
            'class_name.required' => 'Nama kelas wajib diisi.',
            'level.required' => 'Level wajib dipilih.',
            'duration_minutes.required' => 'Durasi wajib diisi.',
        ]);

        $exists = DB::table('classes')->where('class_name', $request->class_name)->exists();
        if ($exists) {
            return back()->withErrors(['class_name' => 'Nama kelas sudah ada.'])->withInput();
        }

        DB::table('classes')->insert([
            'class_name' => $request->class_name,
            'description' => $request->description,
            'level' => $request->level,
            'duration_minutes' => $request->duration_minutes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.classes')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function destroy($classId)
    {
        $hasSchedules = DB::table('schedules')->where('class_id', $classId)->exists();
        if ($hasSchedules) {
            return redirect()->route('admin.classes')
                ->withErrors(['error' => 'Kelas tidak bisa dihapus karena sudah memiliki jadwal.']);
        }

        DB::table('classes')->where('class_id', $classId)->delete();

        return redirect()->route('admin.classes')->with('success', 'Kelas berhasil dihapus.');
    }
}
