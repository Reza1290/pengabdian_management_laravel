<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengabdian extends Model
{
    use HasFactory;

    protected $table = 'pengabdian';

    protected $fillable = ['siswa_id', 'mitra_id', 'pembimbing_id', 'start_date', 'end_date'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function mitra()
    {
        return $this->belongsTo(MitraPengabdian::class, 'mitra_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(PembimbingPengabdian::class, 'pembimbing_id');
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'pengabdian_id');
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'pengabdian_id');
    }
}
