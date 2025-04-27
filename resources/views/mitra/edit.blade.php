<x-app-layout>
    <x-slot name="header">
        <h3>Edit Mitra Pengabdian</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Mitra</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('mitra.update', $mitra) }}" method="POST">
                    @csrf @method('PUT')
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $mitra->nama }}" required>
                    <br>
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $mitra->alamat }}">
                    <br>
                    <label>Kontak</label>
                    <input type="text" name="kontak" class="form-control" value="{{ $mitra->kontak }}">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
