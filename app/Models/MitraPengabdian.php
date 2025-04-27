<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraPengabdian extends Model
{
    use HasFactory;

    protected $table = "mitra_pengabdian";

    protected $fillable = ['nama', 'alamat', 'kontak'];
    
    public function pembimbing() {
        return $this->hasMany(PembimbingPengabdian::class, 'mitra_id');
    }
}
