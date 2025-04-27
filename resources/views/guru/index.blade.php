<x-app-layout>
    <x-slot name="header">
        <h3>Daftar Pengguna</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Guru Aktif</h4>
                <a href="{{ route('guru.pengguna') }}" class="btn btn-primary">Tambah Guru</a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('users.reset', $user->id) }}" class="btn btn-primary">Jadikan Pengguna</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>
