<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotspotProfile;
use App\Models\Voucher;
use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\HotspotUser;
use App\Models\VoucherTemplate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use App\Models\User;


class HotspotUserController extends Controller
{
    /**
     * Tampilkan daftar pengguna hotspot
     */
    public function index(Request $request)
    {
        // Default perPage = 0 -> tampil semua data
        $perPage = (int) $request->get('per_page', 0);

        // Query data voucher + relasi
        $query = Voucher::with(['profile', 'sales']);

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('code', 'like', "%{$request->search}%");
        }

        if ($request->filled('profile') && $request->profile !== '') {
            $query->where('profile_id', $request->profile);
        }

        if ($request->filled('comment') && $request->comment !== '') {
            $query->where('awalan_username', 'like', "%{$request->comment}%");
        }

        // Periksa perPage
        if ($perPage === 0) {
            // Ambil semua data
            $items = $query->orderBy('id', 'desc')->get();
            $users = new LengthAwarePaginator(
                $items,
                $items->count(),
                $items->count() ?: 1,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Batas maksimal
            if ($perPage > 500) {
                $perPage = 500;
            }

            $users = $query->orderBy('id', 'desc')
                           ->paginate($perPage)
                           ->withQueryString();
        }

        $profiles = HotspotProfile::orderBy('nama_profil')->get();

        return view('hotspot.users.index', compact('users', 'profiles', 'perPage'));
    }

    /**
     * Endpoint AJAX untuk ambil data online user dari Mikrotik
     */
    public function getOnlineUsers()
    {
        try {
            $client = new Client([
                'host' => env('MIKROTIK_HOST', '192.168.88.1'),
                'user' => env('MIKROTIK_USER', 'admin'),
                'pass' => env('MIKROTIK_PASS', ''),
                'port' => (int) env('MIKROTIK_PORT', 8728)
            ]);

            $activeUsers = $client->query(new Query('/ip/hotspot/active/print'))->read();
            return response()->json(collect($activeUsers)->pluck('user'));
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = Voucher::with('profile')->findOrFail($id);
        $profiles = HotspotProfile::orderBy('nama_profil')->get();

        return view('hotspot.users.edit', compact('user', 'profiles'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'profile_id' => 'required|integer',
            'limit_uptime' => 'nullable|string|max:50',
            'limit_bytes_total' => 'nullable|string|max:50',
            'comment' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:disable,enable'
        ]);

        $user = Voucher::findOrFail($id);

        // Update field
        $user->code = $request->username;
        $user->profile_id = $request->profile_id;
        $user->batas_waktu = $request->limit_uptime;
        $user->batas_kuota = $request->limit_bytes_total;
        $user->comment = $request->comment;
        $user->status = $request->status === 'disable' ? 0 : 1;

        $user->save();

        return redirect()->route('hotspot.users.index')
                         ->with('success', 'User hotspot berhasil diperbarui.');
    }

    /**
     * Print user yang dipilih
     */
public function printSelected(Request $request)
{
    $ids = $request->input('ids', []);
    $users = Voucher::whereIn('id', $ids)->get(); // <- pakai Voucher, bukan User

    $activeTemplateId = Cache::get('active_voucher_template_id');
    $activeTemplate = VoucherTemplate::find($activeTemplateId);

    if (!$activeTemplate) {
        $activeTemplate = VoucherTemplate::where('is_active', 1)->first();
    }

    if (!$activeTemplate) {
        abort(404, 'Template voucher aktif tidak ditemukan.');
    }

    return view($activeTemplate->view_path, compact('users'));
}


    /**
     * Hapus 1 user
     */
    public function destroy($id)
    {
        try {
            $user = Voucher::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
            }

            $user->delete();

            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus banyak user sekaligus
     */
    public function massDestroy(Request $request)
    {
        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $deleted = Voucher::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} user berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
public function showVoucher($id)
{
    // Ambil voucher berdasarkan ID
    $voucher = Voucher::findOrFail($id);

    // Kirim ke view voucher template
    return view('voucher.show', compact('voucher'));
}

public function showVoucherTemplate($filename)
{
    // Path file di storage
    $path = storage_path('app/public/voucher_templates/' . $filename);

    if (!file_exists($path)) {
        abort(404, "Template voucher tidak ditemukan");
    }

    // Baca isi file HTML voucher
    $htmlContent = file_get_contents($path);

    // Bisa parsing atau replace placeholder di sini jika perlu
    // Contoh ganti {username} dengan data voucher
    $voucher = Voucher::findOrFail(1); // contoh ambil voucher
    $htmlContent = str_replace('{username}', $voucher->code, $htmlContent);

    // Kirim ke view dengan $htmlContent
    return view('strorage/app/voucher_templates/voucher_1666', compact('htmlContent'));
}





}




