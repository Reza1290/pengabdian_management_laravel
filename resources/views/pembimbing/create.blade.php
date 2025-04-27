<x-app-layout>
    <x-slot name="header">
        <h3>Lengkapi Data Pembimbing</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Data Pembimbing</h4>
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

                <form action="{{ route('pembimbing.store', $user->id) }}" method="POST">
                    @csrf
                    <label>Nama</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                    <br>
                    <label>Kontak</label>
                    <input type="text" name="Kontak" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
