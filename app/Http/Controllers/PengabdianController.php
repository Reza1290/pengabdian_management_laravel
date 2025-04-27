<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\Pengabdian;
use App\Models\MitraPengabdian;
use App\Models\PembimbingPengabdian;
use App\Models\Penilaian;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengabdianController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guru'])->except('show');
    }

    public function index()
    {
        $pengabdians = Pengabdian::with(['siswa', 'mitra', 'pembimbing'])->get();
        return view('pengabdian.index', compact('pengabdians'));
    }

    public function create()
    {
        $mitras = MitraPengabdian::all();
        return view('pengabdian.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mitra_id' => 'required|exists:mitra_pengabdian,id',
            'pembimbing_id' => 'required|exists:pembimbing_pengabdian,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Pengabdian::create($request->all());

        return redirect()->route('pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan');
    }

    public function show(Pengabdian $pengabdian)
    {
        if (
            $pengabdian->pembimbing->user->id == Auth::id() ||
            Auth::user()->role == 'guru' ||
            $pengabdian->siswa->user->id == Auth::id()
        ) {
            $start = Carbon::createFromFormat('Y-m-d', $pengabdian->start_date);
            $end = Carbon::createFromFormat('Y-m-d', $pengabdian->end_date);
            $interval = $start->diffInDays($end) + 1;

            $hari = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $laporanHarian = LaporanHarian::where('pengabdian_id', $pengabdian->id)
                ->with('presensi') 
                ->get()
                ->mapWithKeys(function ($laporan) {
                    return [
                        $laporan->tanggal => [
                            'deskripsi' => $laporan->deskripsi ?? '-',
                            'status' => optional($laporan->presensi)->status ?? '-',
                            'isApproved' => optional($laporan->presensi)->isApproved ?? false,
                            'keterangan' => optional($laporan->presensi)->desc ?? '-',
                            'presensi_id' => optional($laporan->presensi)->id
                        ]
                    ];
                });
            
            $todayLaporan = LaporanHarian::where('pengabdian_id',$pengabdian->id)->where('tanggal',Carbon::today())->first();
            $penilaianExist = Penilaian::where('pengabdian_id',$pengabdian->id)->first();

            if($penilaianExist){
                return view('pengabdian.show', compact('pengabdian', 'interval', 'start', 'hari', 'laporanHarian','todayLaporan', 'penilaianExist'));
            }
            return view('pengabdian.show', compact('pengabdian', 'interval', 'start', 'hari', 'laporanHarian','todayLaporan'));
        } else {
            abort(403);
        }
    }

    public function edit(Pengabdian $pengabdian)
    {
        $mitras = MitraPengabdian::all();
        $pembimbings = PembimbingPengabdian::all();
        $siswas = Siswa::all();
        return view('pengabdian.edit', compact('pengabdian', 'mitras', 'pembimbings', 'siswas'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mitra_id' => 'required|exists:mitra_pengabdian,id',
            'pembimbing_id' => 'required|exists:pembimbing_pengabdian,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $pengabdian->update($request->all());

        return redirect()->route('pengabdian.index')->with('success', 'Pengabdian berhasil diperbarui');
    }

    public function destroy(Pengabdian $pengabdian)
    {
        $pengabdian->delete();
        return redirect()->route('pengabdian.index')->with('success', 'Pengabdian berhasil dihapus');
    }

    public function pembimbingAssign()
    {
        // $mitra = MitraPengabdian::findOrFail();
        // $assignedPembimbingIds = PembimbingPengabdian::pluck('user_id')->toArray();

        $pembimbing = PembimbingPengabdian::with('user')->with('mitra')->get();

        return view('pengabdian.pembimbing.index', compact('pembimbing'));
    }

    public function siswaAssign($pembimbing_id)
    {
        $siswa = Siswa::doesntHave('pengabdian')->get();
        $pembimbing = $pembimbing_id;
        return view('pengabdian.pembimbing.siswa', compact('pembimbing', 'siswa'));
    }



    public function storeSiswaAssign(Request $request, $pembimbing_id, $siswa_id)
    {
        $pembimbing = PembimbingPengabdian::with('mitra')->findOrFail($pembimbing_id);
        $siswa = Siswa::findOrFail($siswa_id);

        return view('pengabdian.create', compact('pembimbing', 'siswa'));
    }
}
