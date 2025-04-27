<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\Pengabdian;
use App\Models\Penilaian;
use App\Models\Presensi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Phpml\Regression\LeastSquares;

class PenilaianController extends Controller
{
    public function __construct() {
        $this->middleware(['pembimbing'])->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();

        if (in_array(Auth::user()->role, ['pembimbing', 'guru'])) {
            $pengabdians = Pengabdian::with(['siswa', 'mitra', 'pembimbing'])
                ->whereHas('pembimbing', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
        } else {
            $pengabdians = Pengabdian::with(['siswa', 'mitra', 'pembimbing'])
                ->whereHas('siswa', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
        }
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
        $request->validate([
            'pengabdian_id' => 'required',
            'catatan' => 'nullable|string',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $pengabdian = Pengabdian::find($request->pengabdian_id);

        if (!$pengabdian || $pengabdian->pembimbing->user_id != Auth::id()) {
            return response()->json([
                'message' => 'Error bukan',
            ]);
        }
        $jumlah_laporan = LaporanHarian::where('pengabdian_id', $request->pengabdian_id)->count();

        $startDate = Carbon::parse($pengabdian->start_date);
        $endDate = Carbon::parse($pengabdian->end_date);

        $jumlah_hari = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) { 
                $jumlah_hari++;
            }
            $currentDate->addDay();
        }

        // Hitung presensi hadir
        $jumlah_kehadiran = Presensi::whereHas('laporanHarian', function ($query) use ($pengabdian) {
            $query->where('pengabdian_id', $pengabdian->id);
        })->whereIn('status', ['hadir'])->count();

        $persen_laporan = ($jumlah_laporan > 0) ? ($jumlah_laporan / $jumlah_hari) * 10 : 0;
        
        // Hitung presensi alpha
        $jumlah_kehadiran_alpha = Presensi::whereHas('laporanHarian', function ($query) use ($pengabdian) {
            $query->where('pengabdian_id', $pengabdian->id);
        })->where('status', 'alpha')->count();

        // Hitung jumlah bolos
        $jumlah_bolos = $jumlah_kehadiran_alpha;
        $jumlah_bolos = round(max(0, min(25, $jumlah_bolos)));
        $persen_bolos = $jumlah_bolos > 0 ? ($jumlah_bolos / 25) *10 : 0;

        // Hitung persentase kehadiran
        $persen_kehadiran = ($jumlah_laporan > 0) ? ($jumlah_kehadiran / $jumlah_hari) * 10 : 0;

        $penilaian = Penilaian::updateOrCreate([
            'pengabdian_id' => $request->pengabdian_id
        ],[
            'pengabdian_id' => $request->pengabdian_id,
            'persen_laporan' => $persen_laporan,
            'persen_bolos' => $persen_bolos,
            'persen_kehadiran' => $persen_kehadiran,
            'nilai' => intval(round($request->nilai)),
            'catatan' => $request->catatan ?? "-"
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil Disimpan');
    }

    public function predict(Request $request)
    {
        // Validasi pengabdian milik user
        $pengabdian = Pengabdian::with('pembimbing')->find($request->pengabdian_id);

        if (!$pengabdian || $pengabdian->pembimbing->user_id != Auth::id()) {
            return response()->json([
                'message' => 'Pengabdian tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        $cacheKey = 'predict_nilai_pengabdian_' . $pengabdian->id;

        // Cek apakah hasil sudah ada di cache
        if (Cache::has($cacheKey)) {
            return response()->json([
                'predicted_nilai' => Cache::get($cacheKey),
                'message' => 'Diambil dari cache'
            ]);
        }

        $jumlah_laporan = LaporanHarian::where('pengabdian_id', $pengabdian->id)->count();

        $startDate = Carbon::parse($pengabdian->start_date);
        $endDate = Carbon::parse($pengabdian->end_date);

        $jumlah_hari = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) { 
                $jumlah_hari++;
            }
            $currentDate->addDay();
        }

        // Hitung presensi hadir
        $jumlah_kehadiran = Presensi::whereHas('laporanHarian', function ($query) use ($pengabdian) {
            $query->where('pengabdian_id', $pengabdian->id);
        })->whereIn('status', ['hadir'])->count();

        $persen_laporan = ($jumlah_laporan > 0) ? ($jumlah_laporan / $jumlah_hari) * 10 : 0;
        Log::info($jumlah_hari);
        // Hitung presensi alpha
        $jumlah_kehadiran_alpha = Presensi::whereHas('laporanHarian', function ($query) use ($pengabdian) {
            $query->where('pengabdian_id', $pengabdian->id);
        })->where('status', 'alpha')->count();

        // Hitung jumlah bolos
        $jumlah_bolos = $jumlah_kehadiran_alpha;
        $jumlah_bolos = round(max(0, min(25, $jumlah_bolos)));
        $persen_bolos = $jumlah_bolos > 0 ? ($jumlah_bolos / 25) *10 : 0;

        // Hitung persentase kehadiran
        $persen_kehadiran = ($jumlah_laporan > 0) ? ($jumlah_kehadiran / $jumlah_hari) * 10 : 0;

        // Validasi minimal data
        $penilaian = Penilaian::all();

        if ($penilaian->count() < 10) {
            $filePath = storage_path('app/public/datasets/dummy_init.json');

            if (file_exists($filePath)) {
                $dummyJson = file_get_contents($filePath);
                $dummyData = collect(json_decode($dummyJson));
                $penilaian = $dummyData;
            } else {
                return response()->json([
                    'message' => 'File dummy data tidak ditemukan.'
                ], 500);
            }
        }

        if ($penilaian->count() > 1000) {
            return response()->json([
                'message' => 'Data terlalu besar, training model harus manual.'
            ], 500);
        }

        // Siapkan samples dan labels
        $samples = $penilaian->map(function ($item) {
            return [
                $item->persen_laporan,
                $item->persen_bolos,
                $item->persen_kehadiran,
            ];
        })->toArray();

        $labels = $penilaian->pluck('nilai')->toArray();

        Log::info($persen_bolos);
        Log::info($persen_laporan);
        // Train model
        $regression = new LeastSquares();
        $regression->train($samples, $labels);

        // Prediksi nilai
        $predictedNilai = $regression->predict([
            $persen_laporan,
            $persen_bolos,
            $persen_kehadiran,
        ]);

        $predictedNilai = round(max(40, min(100, $predictedNilai)));

        // Simpan ke cache
        Cache::put($cacheKey, $predictedNilai, now()->addHours(1));

        return response()->json([
            'predicted_nilai' => $predictedNilai,
            'message' => 'Hasil baru, disimpan ke cache'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function show(Penilaian $penilaian) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function edit(Penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penilaian $penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penilaian $penilaian)
    {
        //
    }
}
