<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\Pengabdian;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id(); 

        $pengabdians = Pengabdian::with(['siswa', 'mitra', 'pembimbing'])
            ->whereHas('siswa', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
        
        return view('penilaian.index', compact('pengabdians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            "deskripsi" => "required|string",
            "pengabdian_id" => "required",
        ]);
        $pengabdian = Pengabdian::findOrFail($request->pengabdian_id);


        if ($pengabdian->siswa->user->id != $userId) abort(403);

        $today = Carbon::today()->toDateString();
        if ($today < $pengabdian->start_date || $today > $pengabdian->end_date) {
            return redirect()->back()->with('error', 'Periode Pengabdian Berakhir Atau Belum Dimulai');
        }

        $laporan_harian = LaporanHarian::updateOrCreate(
            [
                "tanggal" => $today,
                "pengabdian_id" => $request->pengabdian_id, 
            ],
            [
                "deskripsi" => $request->deskripsi 
            ]
        );

        $presensi = Presensi::create([
            'laporan_harian_id' => $laporan_harian->id,
            'tanggal' => $today,
            'status' => '-',
            'isApproved' => false
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LaporanHarian  $laporanHarian
     * @return \Illuminate\Http\Response
     */
    public function show(LaporanHarian $laporanHarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LaporanHarian  $laporanHarian
     * @return \Illuminate\Http\Response
     */
    public function edit(LaporanHarian $laporanHarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanHarian  $laporanHarian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LaporanHarian $laporanHarian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LaporanHarian  $laporanHarian
     * @return \Illuminate\Http\Response
     */
    public function destroy(LaporanHarian $laporanHarian)
    {
        //
    }
}
