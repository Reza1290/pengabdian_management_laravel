<x-app-layout>
    <x-slot name="header">
        <h3>Edit Pengabdian</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Pengabdian</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pengabdian.update', $pengabdian->id) }}" method="POST">
                    @csrf @method('PUT')
                    <label>Nama Mitra</label>
                    <input type="text" name="nama_mitra" class="form-control" value="{{ $pengabdian->nama }}" required>
                    <br>
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ $pengabdian->alamat }}" required>
                    <br>
                    <label>Periode Pengabdian</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $pengabdian->start_date }}" required>
                    <br>
                    <input type="date" name="end_date" class="form-control" value="{{ $pengabdian->end_date }}" required>
                    <br>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
