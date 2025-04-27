<x-app-layout>
    <x-slot name="header">
        <h3>Tambah Mitra Pengabdian</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Mitra</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('mitra.store') }}" method="POST">
                    @csrf
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                    <br>
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control">
                    <br>
                    <label>Kontak</label>
                    <input type="text" name="kontak" class="form-control">
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>