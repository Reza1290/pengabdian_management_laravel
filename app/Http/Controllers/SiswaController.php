<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function __construct() {
        // $this->middleware('auth');
        $this->middleware('guru');
    }

    public function index()
    {
        $users = User::where('role', 'siswa')->get();
        return view('siswa.index', compact('users'));
    }
    
    public function pengguna()
    {
        $users = User::where('role', 'pengguna')->get();
        return view('siswa.pengguna.index', compact('users'));
    }

    public function resetRole($id){

        $user = User::findOrFail($id);
        
        $siswa = Siswa::where('user_id', $id)->first();
        
        if($siswa){
            $siswa->delete();   
        }

        $user->role = "pengguna";
        $user->save();

        return redirect()->back()->with('success','User dipulihkan ke Pengguna');
    }


    public function create($id)
    {
        $user = User::findOrFail($id);
        return view('siswa.create', compact('user'));
    }

    public function store(Request $request, $user_id)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'kelas' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
        ]);


        $user = User::findOrFail($user_id);

        $user->role = 'siswa';
        $user->save();

        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Pengguna berhasil dijadikan siswa.');
    }
}
