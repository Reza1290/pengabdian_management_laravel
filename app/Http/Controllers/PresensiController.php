<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $request->validate([
            // 'tanggal' => 'required|date',
            'isApproved' => 'nullable',
            'status' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        // $laporan = LaporanHarian::where('tanggal', $request->tanggal)
        //     ->where('pengabdian_id', Auth::user()->pengabdian->id)
        //     ->first();

        if (!$presensi) {
            return response()->json(['error' => 'Laporan harian tidak ditemukan'], 404);
        }

        $presensi->update(['isApproved' => !$presensi->isApproved]);
        // if ($request->has('isApproved')) {
        //     $presensi->update(['isApproved' => !$presensi->isApproved]);
        // }else{

        // }

        if ($request->has('status')) {
            $presensi->update(['status' => $request->status]);
        }

        if ($request->has('deskripsi')) {
            $presensi->update(['deskripsi' => $request->deskripsi]);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function show(Presensi $presensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function edit(Presensi $presensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presensi $presensi) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presensi $presensi)
    {
        //
    }

    public function updateState(Request $request, $id)
    {
        // Find the presensi record or return 404
        $presensi = Presensi::findOrFail($id);
        
        // Validate the request
        $request->validate([
            'isApproved' => 'nullable|boolean',
            'status' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        // Update only the provided fields
        
        if ($request->has('isApproved')) {
            $presensi->isApproved = $request->isApproved; 
        }
        
        if ($request->has('status')) {
            $presensi->status =  strtolower($request->status);
        }

        if ($request->has('deskripsi')) {
            $presensi->desc = $request->deskripsi;
        }
        $presensi->save();
        // Return success response for AJAX
        return response()->json(['message' => 'Presensi updated successfully'], 200);
    }
}
