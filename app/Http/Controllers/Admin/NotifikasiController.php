<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
   public function index()
{
    $notifikasis = \App\Models\Notifikasi::with('user')
        ->orderByDesc('tg_kirim')
        ->paginate(20);

    $totalNotif   = \App\Models\Notifikasi::count();
    $belumDibaca  = \App\Models\Notifikasi::where('status_dibaca', false)->count();
    $sudahDibaca  = \App\Models\Notifikasi::where('status_dibaca', true)->count();
    $notifHariIni = \App\Models\Notifikasi::whereDate('tg_kirim', today())->count();

    return view('admin.notifikasi', compact(
        'notifikasis', 'totalNotif', 'belumDibaca', 'sudahDibaca', 'notifHariIni'
    ));
}

public function markAsRead($id)
{
    $notif = \App\Models\Notifikasi::findOrFail($id);
    $notif->update(['status_dibaca' => true]);

    return response()->json(['success' => true]);
}

public function destroy($id)
{
    $notif = \App\Models\Notifikasi::findOrFail($id);
    $notif->delete();

    return response()->json(['success' => true]);
}
}