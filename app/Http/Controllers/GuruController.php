<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'guru')->get();
        return view('guru.index', compact('users'));
    }
    
    public function pengguna()
    {
        $users = User::where('role', 'pengguna')->get();
        return view('guru.pengguna.index', compact('users'));
    }


    public function create($id)
    {
        $user = User::findOrFail($id);

        $user->role = 'guru';
        $user->save();

        

        return redirect()->route('guru.index')->with('success', 'Pengguna berhasil dijadikan guru.');
    }

    
}
