<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = DB::table('promotions')->orderBy('created_at', 'desc')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name')
            ->get();

        return view('admin.promotions', [
            'promotions' => $promotions,
            'coaches'    => $coaches,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:100',
            'description'    => 'nullable|string',
            'original_price' => 'required|string|max:20',
            'promo_price'    => 'required|string|max:20',
            'tags'           => 'nullable|string|max:255',
            'coach_name'     => 'nullable|string|max:100',
            'schedule_date'  => 'nullable|date',
            'start_time'     => 'nullable',
            'end_time'       => 'nullable',
            'pertemuan'      => 'nullable|integer|min:1',
        ], [
            'title.required'          => 'Judul wajib diisi.',
            'original_price.required' => 'Harga asli wajib diisi.',
            'promo_price.required'    => 'Harga promo wajib diisi.',
        ]);

        DB::table('promotions')->insert([
            'title'          => $request->title,
            'description'    => $request->description,
            'original_price' => $request->original_price,
            'promo_price'    => $request->promo_price,
            'tags'           => $request->tags,
            'coach_name'     => $request->coach_name,
            'schedule_date'  => $request->schedule_date,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'pertemuan'      => $request->pertemuan,
            'is_active'      => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('admin.promotions')->with('success', 'Penawaran berhasil ditambahkan!');
    }

    public function toggleActive($id)
    {
        $promo = DB::table('promotions')->where('promo_id', $id)->first();
        if ($promo) {
            DB::table('promotions')->where('promo_id', $id)->update([
                'is_active'  => $promo->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.promotions')
            ->with('success', 'Status penawaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('promotions')->where('promo_id', $id)->delete();

        return redirect()->route('admin.promotions')->with('success', 'Penawaran berhasil dihapus.');
    }
}
