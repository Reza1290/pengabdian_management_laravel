<x-app-layout>
    <x-slot name="header">
        <h3>Tambahkan Pembimbing ke {{ $mitra->nama }}</h3>
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pilih Pembimbing</h4>
                <input type="text" id="searchInput" class="form-control mt-2" placeholder="Cari Pembimbing...">
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pembimbingTable">
                        @foreach($pembimbing as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                <form action="{{ route('pembimbing.storeAssign', $mitra->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="pembimbing_id" value="{{ $p->id }}">
                                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#pembimbingTable tr");

            rows.forEach(row => {
                let nama = row.cells[0].textContent.toLowerCase();
                let email = row.cells[1].textContent.toLowerCase();
                if (nama.includes(filter) || email.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</x-app-layout>
