<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotspotUser;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class VoucherLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('voucher.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            // jika voucher tidak pakai password, hapus line berikut
            'password' => 'nullable',
        ]);

        $voucher = HotspotUser::where('username', $request->username)
            ->when($request->password, function ($q) use ($request) {
                $q->where('password', $request->password);
            })
            ->first();

        if (!$voucher) {
            return back()->withErrors(['login' => 'Username atau password salah.']);
        }

        if ($voucher->status === 'online') {
            return back()->withErrors(['login' => 'Voucher ini sedang digunakan di perangkat lain.']);
        }

        // Deteksi perangkat
        $agent = new Agent();
        $device = $agent->device() ?: 'Unknown Device';
        $platform = $agent->platform() ?: 'Unknown OS';

        $voucher->update([
            'device_name' => "$device - $platform",
            'ip_address'  => $request->ip(),
            'status'      => 'online',
            'login_time'  => Carbon::now(),
            'comment'     => 'User logged in successfully',
        ]);

        // Simpan session
        Session::put('voucher_id', $voucher->id);

        return redirect()->route('dashboard')->with('success', 'Login berhasil.');
    }

    public function logout()
    {
        $voucherId = Session::get('voucher_id');
        $voucher = HotspotUser::find($voucherId);

        if ($voucher) {
            $voucher->update([
                'status'      => 'offline',
                'logout_time' => Carbon::now(),
            ]);
        }

        Session::forget('voucher_id');

        return redirect()->route('voucher.login.form')->with('success', 'Logout berhasil.');
    }
}
