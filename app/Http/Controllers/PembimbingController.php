<?php

namespace App\Http\Controllers;

use App\Models\MitraPengabdian;
use App\Models\PembimbingPengabdian;
use App\Models\User;
use Illuminate\Http\Request;

class PembimbingController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'pembimbing')->get();
        return view('pembimbing.index', compact('users'));
    }

    public function pengguna()
    {
        $users = User::where('role', 'pengguna')->get();
        return view('pembimbing.pengguna.index', compact('users'));
    }

    public function create($id)
    {
        $user = User::findOrFail($id);

        $user->role = 'pembimbing';
        $user->save();

        PembimbingPengabdian::create([
            'kontak' => null,
            'mitra_id' => null,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Pengguna berhasil dijadikan siswa.');
    }

    // public function store(Request $request, $user_id)
    // {

    // }


    public function assign($mitra_id)
    {
        $mitra = MitraPengabdian::findOrFail($mitra_id);
        $assignedPembimbingIds = PembimbingPengabdian::pluck('user_id')->toArray();

        $pembimbing = User::where('role', 'pengguna')
            ->whereNotIn('id', $assignedPembimbingIds)
            ->get();

        return view('pembimbing.assign', compact('mitra', 'pembimbing'));
    }

    public function storeAssign(Request $request, $mitra_id)
    {
        $mitra = MitraPengabdian::findOrFail($mitra_id);

            $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->pembimbing_id);

        $existingPembimbing = PembimbingPengabdian::where('user_id', $user->id)
            ->where('mitra_id', $mitra_id)
            ->first();

        if ($existingPembimbing) {
            return redirect()->route('mitra.show', $mitra_id)
                ->with('error', 'Pembimbing sudah terdaftar di mitra ini.');
        }

        $user->role = 'pembimbing';
        $user->save();

        $pembimbing = PembimbingPengabdian::create([
            'user_id' => $user->id,
            'kontak' => null,
            'mitra_id' => $mitra_id,
        ]);

        if (method_exists($mitra->pembimbing(), 'attach')) {
            $mitra->pembimbing()->attach($pembimbing->id);
        }

        return redirect()->route('mitra.show', $mitra_id)
            ->with('success', 'Pembimbing berhasil ditambahkan.');
    }

    public function detach($mitra_id, $pembimbing_id)
    {
        $mitra = MitraPengabdian::findOrFail($mitra_id);
        $pembimbing = $mitra->pembimbing()->where('id', $pembimbing_id)->firstOrFail();
        $pembimbing->user->role = 'pengguna';
        $pembimbing->user->save();

        $pembimbing->delete();

        return redirect()->route('mitra.show', $mitra_id)->with('success', 'Pembimbing berhasil dihapus.');
    }
}
