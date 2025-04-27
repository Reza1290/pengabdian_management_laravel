<x-app-layout>
    <x-slot name="header">
        <h3>Tambah Pengabdian</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Pengabdian</h4>
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
                <form action="{{ route('pengabdian.store') }}" method="POST">
                    @csrf
                    <input type="text" name="pembimbing_id" value="{{$pembimbing->id}}" readonly hidden>
                    <input type="text" name="mitra_id" value="{{$pembimbing->mitra_id}}" readonly hidden>
                    <input type="text" name="siswa_id" value="{{$siswa->id}}" readonly hidden>
                    <label>Nama Mitra</label>
                    <input type="text" name="nama_mitra" class="form-control" value="{{$pembimbing->mitra->nama}}" readonly>
                    <br>
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{$pembimbing->mitra->alamat}}" readonly>
                    <br>
                    <label>Siswa</label>
                    <input type="text" name="siswa" class="form-control" value="{{$siswa->user->name}}" readonly>
                    <br>
                    <label>Periode Pengabdian</label>
                    <input type="date" name="start_date" class="form-control" required>
                    <br>
                    <input type="date" name="end_date" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
