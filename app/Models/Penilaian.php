<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = ['pengabdian_id', 'nilai', 'catatan','persen_kehadiran','persen_laporan','persen_bolos'];

    public function pengabdian()
    {
        return $this->belongsTo(Pengabdian::class, 'pengabdian_id');
    }
}
