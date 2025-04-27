<x-app-layout>
    <x-slot name="header">
        <h3>Daftar Pengabdian</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Mitra</th>
                            <th>Lokasi</th>
                            <th>Nama Pembimbing</th>
                            <th>Nama Siswa</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengabdians as $pengabdian)
                        <tr>
                            <td>{{ $pengabdian->mitra->nama }}</td>
                            <td>{{ $pengabdian->mitra->alamat }}</td>
                            <td>{{ $pengabdian->pembimbing->user->name }}</td>
                            <td>{{ $pengabdian->siswa->user->name }}</td>
                            <td>{{ $pengabdian->start_date }} s.d {{ $pengabdian->end_date }}</td>
                            <td>
                                <a href="{{ route('pengabdian.show', $pengabdian->id) }}" class="btn btn-primary">Detail</a>
                                <!-- <form action="{{ route('pengabdian.destroy', $pengabdian->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>
