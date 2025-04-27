<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $table = 'laporan_harian';

    protected $fillable = ['pengabdian_id', 'tanggal', 'deskripsi', 'foto'];

    public function pengabdian()
    {
        return $this->belongsTo(Pengabdian::class, 'pengabdian_id');
    }

    public function presensi()
    {
        return $this->hasOne(Presensi::class, 'laporan_harian_id');
    }
}
