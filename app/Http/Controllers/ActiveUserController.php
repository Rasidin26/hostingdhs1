<?php

namespace App\Http\Controllers;

use App\Models\HotspotUser;
use Carbon\Carbon;

class ActiveUserController extends Controller
{
    public function index()
    {
        $activeUsers = HotspotUser::with([
            'sales.user',     // user yang melakukan penjualan
            'profile',        // profil paket
            'router',         // router mikrotik
            'sales.vouchers'  // voucher yang terjual
        ])
        ->where('status', 'online')
        ->get()
        ->filter(function ($user) {
            if (!$user->login_time) return true;

            $loginTime = Carbon::parse($user->login_time);
            $endTime = $loginTime->copy()->addHours(7);

            return now()->lessThan($endTime);
        })
        ->values(); // reset index setelah filter

        return view('active_users.index', compact('activeUsers'));
    }
}
