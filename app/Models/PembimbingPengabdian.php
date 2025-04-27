<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingPengabdian extends Model
{
    use HasFactory;

    protected $table = "pembimbing_pengabdian";

    protected $fillable = ['user_id','mitra_id', 'nama', 'kontak'];

    public function mitra()
    {
        return $this->belongsTo(MitraPengabdian::class, 'mitra_id');
    }
    
    public function pengabdian()
    {
        return $this->hasMany(Pengabdian::class, 'pembimbing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
