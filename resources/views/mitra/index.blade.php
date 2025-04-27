<x-app-layout>
    <x-slot name="header">
        <h3>Manajemen Mitra Pengabdian</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Mitra Pengabdian</h4>
                <a href="{{ route('mitra.create') }}" class="btn btn-primary">Tambah Mitra</a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mitra as $m)
                        <tr>
                            <td>{{ $m->nama }}</td>
                            <td>{{ $m->alamat }}</td>
                            <td>
                                <a href="{{ route('mitra.show', $m) }}" class="btn btn-info">Detail</a>
                                <a href="{{ route('mitra.edit', $m) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('mitra.destroy', $m) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>
