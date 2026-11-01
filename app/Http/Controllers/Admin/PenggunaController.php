<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $penggunas = User::query()
            ->when($request->q, function ($q, $cari) {
                $q->where(function ($w) use ($cari) {
                    $w->where('name', 'like', "%{$cari}%")
                      ->orWhere('email', 'like', "%{$cari}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $jumlah = Diagnosa::selectRaw('user_id, COUNT(*) as total')
            ->whereIn('user_id', $penggunas->pluck('id'))
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return view('admin.pengguna.index', compact('penggunas', 'jumlah'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate(['role' => 'required|in:user,admin']);

        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa mengubah role akunmu sendiri.');
        }

        $pengguna->forceFill(['role' => $data['role']])->save();

        return back()->with('sukses', "Role {$pengguna->name} diubah menjadi {$data['role']}.");
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $nama = $pengguna->name;
        $pengguna->delete();

        return back()->with('sukses', "Pengguna {$nama} dihapus. Riwayat diagnosanya tetap tersimpan sebagai tamu.");
    }
}