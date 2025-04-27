<?php

namespace App\Http\Controllers;

use App\Models\MitraPengabdian;
use App\Models\PembimbingPengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraPengabdianController extends Controller
{   
    public function index()
    {
        $mitra = MitraPengabdian::all();
        return view('mitra.index', compact('mitra'));
    }

    public function create()
    {
        return view('mitra.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
        ]);

        MitraPengabdian::create($request->all());

        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil ditambahkan');
    }

    public function show(MitraPengabdian $mitra)
    {
        return view('mitra.show', compact('mitra'));
    }

    public function showMyMitra(MitraPengabdian $mitra)
    {
        $user = Auth::user()->id;

        $pembimbing = PembimbingPengabdian::where('user_id', $user)->first();
        if($pembimbing){
            $mitra = $pembimbing->mitra;
            return view('mitra.show', compact('mitra'));
        }else{
            return redirect()->route('dashboard');
        }
    }

    public function edit(MitraPengabdian $mitra)
    {
        return view('mitra.edit', compact('mitra'));
    }

    public function update(Request $request, MitraPengabdian $mitra)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
        ]);

        $mitra->update($request->all());

        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil diperbarui');
    }

    public function destroy(MitraPengabdian $mitra)
    {
        $mitra->delete();
        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil dihapus');
    }
}
