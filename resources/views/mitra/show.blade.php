<x-app-layout>
    <x-slot name="header">
        <h3>Detail Mitra Pengabdian</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informasi Mitra</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $mitra->nama }}</p>
                <p><strong>Alamat:</strong> {{ $mitra->alamat }}</p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Daftar Pembimbing</h4>
                @if(Auth::user()->role == 'guru')
                <a href="{{ route('pembimbing.assign', $mitra->id) }}" class="btn btn-primary">Tambah Pembimbing</a>
                @endif
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            @if(Auth::user()->role == 'guru')
                            <th>Aksi</th>   
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mitra->pembimbing as $pembimbing)
                        <tr>
                            <td>{{ $pembimbing->user->name }}</td>
                            <td>{{ $pembimbing->user->email }}</td>
                            @if(Auth::user()->role == 'guru')
                            <td>
                                <form action="{{ route('pembimbing.detach', [$mitra->id, $pembimbing->id]) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>