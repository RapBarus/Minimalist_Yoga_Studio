<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    public function index()
    {
        $packages = DB::table('membership_packages')
            ->orderBy('created_at', 'desc')
            ->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name')
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();
        return view('admin.membership', compact('packages', 'coaches', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'class_id' => 'required|integer',
            'quota_amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'validity_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric|min:0',
        ], [
            'name.required' => 'Nama paket wajib diisi.',
            'quota_amount.required' => 'Jumlah sesi wajib diisi.',
            'quota_amount.min' => 'Jumlah sesi minimal 1.',
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'validity_months.required' => 'Masa aktif wajib diisi.',
            'validity_months.min' => 'Masa aktif minimal 1 bulan.',
        ]);

        if ($request->original_price && $request->price > $request->original_price) {
            return back()
                ->withErrors(['price' => 'Harga diskon tidak boleh lebih besar dari harga asli.'])
                ->withInput();
        }

        DB::table('membership_packages')->insert([
            'name' => $request->name,
            'class_id' => $request->class_id,
            'quota_amount' => $request->quota_amount,
            'price' => $request->price,
            'original_price' => $request->original_price ?: null,
            'validity_months' => $request->validity_months,
            'description' => $request->description,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.membership')
            ->with('success', 'Paket membership berhasil ditambahkan!');
    }

    public function toggleActive($id)
    {
        $package = DB::table('membership_packages')->where('package_id', $id)->first();
        if ($package) {
            DB::table('membership_packages')->where('package_id', $id)->update([
                'is_active' => $package->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.membership')
            ->with('success', 'Status paket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Check if any quotas reference this package
        $hasQuotas = DB::table('membership_quotas')->where('package_id', $id)->exists();
        if ($hasQuotas) {
            return redirect()->route('admin.membership')
                ->withErrors(['error' => 'Paket tidak bisa dihapus karena sudah ada member yang membeli.']);
        }

        DB::table('membership_packages')->where('package_id', $id)->delete();

        return redirect()->route('admin.membership')
            ->with('success', 'Paket membership berhasil dihapus.');
    }
}
