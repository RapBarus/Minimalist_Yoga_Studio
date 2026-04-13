<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        $promos = DB::table('promos')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.promos', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'max_uses' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ], [
            'code.required' => 'Kode promo wajib diisi.',
            'code.unique' => 'Kode promo sudah digunakan.',
            'discount_type.required' => 'Tipe diskon wajib dipilih.',
            'discount_value.required' => 'Nilai diskon wajib diisi.',
            'discount_value.min' => 'Nilai diskon minimal 1.',
            'max_uses.required' => 'Maks penggunaan wajib diisi.',
            'valid_from.required' => 'Tanggal mulai wajib diisi.',
            'valid_until.required' => 'Tanggal berakhir wajib diisi.',
            'valid_until.after_or_equal' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        // Extra validation: percentage max 100
        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return back()
                ->withErrors(['discount_value' => 'Diskon persentase maksimal 100%.'])
                ->withInput();
        }

        DB::table('promos')->insert([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_uses' => $request->max_uses,
            'used_count' => 0,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.promos')
            ->with('success', 'Kode promo ' . strtoupper($request->code) . ' berhasil ditambahkan!');
    }

    public function toggleActive($id)
    {
        $promo = DB::table('promos')->where('promo_id', $id)->first();
        if ($promo) {
            DB::table('promos')->where('promo_id', $id)->update([
                'is_active' => $promo->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.promos')
            ->with('success', 'Status promo berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hasUsages = DB::table('promo_usages')->where('promo_id', $id)->exists();
        if ($hasUsages) {
            return redirect()->route('admin.promos')
                ->withErrors(['error' => 'Promo tidak bisa dihapus karena sudah pernah digunakan.']);
        }

        DB::table('promos')->where('promo_id', $id)->delete();

        return redirect()->route('admin.promos')
            ->with('success', 'Kode promo berhasil dihapus.');
    }
}
