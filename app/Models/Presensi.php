<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = ['laporan_harian_id', 'tanggal', 'status', 'desc', 'isApproved'];
    
    public function laporanHarian() {
        return $this->belongsTo(LaporanHarian::class, 'laporan_harian_id');
    }
}
