<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\HotspotProfile;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HotspotVoucherController extends Controller
{
    public function create()
    {
        $sales = Sales::all();
        $profiles = HotspotProfile::all();
        return view('voucher.create', compact('sales', 'profiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id'        => 'required|exists:sales,id',
            'profile_id'      => 'required|exists:hotspot_profiles,id',
            'jumlah'          => 'required|integer|min:1|max:25000',
            'tipe_pengguna'   => 'required|in:user,admin',
            'tipe_karakter'   => 'required|in:angka,huruf,campuran',
            'awalan_username' => 'nullable|string|max:10',
            'batas_waktu'     => 'nullable|string|max:50',
            'batas_kuota'     => 'nullable|string|max:10',
            'price'           => 'nullable|numeric',
        ]);

        $jumlah       = $request->jumlah;
        $tipeKarakter = $request->tipe_karakter;
        $awalan       = $request->awalan_username ?? '';
        $batasWaktu   = $request->batas_waktu;
        $batasKuota   = $request->batas_kuota;
        $profile      = HotspotProfile::findOrFail($request->profile_id);

$price = $request->price ?? $profile->harga_jual ?? 0;

        // Hitung expired_at jika batas waktu diisi
        $expiredAt = null;
        if ($batasWaktu) {
            $lower = strtolower($batasWaktu);
            if (preg_match('/(\d+)\s*jam/', $lower, $match)) {
                $expiredAt = Carbon::now()->addHours((int) $match[1]);
            } elseif (preg_match('/(\d+)\s*hari/', $lower, $match)) {
                $expiredAt = Carbon::now()->addDays((int) $match[1]);
            } elseif (preg_match('/(\d+)\s*menit/', $lower, $match)) {
                $expiredAt = Carbon::now()->addMinutes((int) $match[1]);
            }
        }

        for ($i = 0; $i < $jumlah; $i++) {
            $code = $awalan . $this->generateRandomString(6, $tipeKarakter);

          $voucher = Voucher::create([
    'code'            => mb_strtoupper($code),
    'price'           => $price,
    'status'          => $request->status ?? 'offline', // sesuai enum: 'offline' atau 'online'
    'sales_id'        => $request->sales_id,
    'profile_id'      => $request->profile_id,
    'jumlah'          => 1,
    'tipe_pengguna'   => $request->tipe_pengguna,
    'awalan_username' => $awalan,
    'tipe_karakter'   => $tipeKarakter,
    'batas_waktu'     => $batasWaktu,
    'expired_at'      => $expiredAt,
    'batas_kuota'     => $batasKuota ?: null,
    'used_at'         => null, // tandai voucher belum dipakai
]);

Log::info('Voucher berhasil dibuat', $voucher->toArray());

        }

        return redirect()->route('hotspot.users.index')
                         ->with('success', 'Voucher berhasil dibuat.');
    }

    private function generateRandomString($length = 6, $type = 'campuran')
    {
        switch (strtolower($type)) {
            case 'angka':
                $characters = '0123456789';
                break;
            case 'huruf':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $randomString = '';
        $max = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $max)];
        }

        return $randomString;
    }

    public function destroy($id)
{
    try {
        $voucher = Voucher::findOrFail($id); // pastikan pakai model Voucher
        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus voucher.'
        ], 500);
    }
}

public function massDestroy(Request $request)
{
    try {
        $ids = $request->input('ids', []);
        Voucher::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher terpilih berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus voucher terpilih.'
        ], 500);
    }
}


}
