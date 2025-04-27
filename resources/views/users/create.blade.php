<x-app-layout>
    <x-slot name="header">
        <h3>Tambah Pengguna</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Pengguna</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                    <br>
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                    <br>
                    <!-- <label>Role</label> -->
                    <!-- <select name="role" class="form-control" required>
                        <option value="pengguna">Pengguna</option>
                        <option value="guru">Guru</option>
                        <option value="pembimbing">Pembimbing</option>
                        <option value="admin">Admin</option>
                    </select> -->

                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>