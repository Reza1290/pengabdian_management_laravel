<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = ['user_id','nis','kelas','jurusan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengabdian()
    {
        return $this->hasMany(Pengabdian::class, 'siswa_id');
    }
}
