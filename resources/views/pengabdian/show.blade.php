<x-app-layout>
    <x-slot name="header">
        <h3>Detail Pengabdian Siswa</h3>
    </x-slot>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Pengabdian</h4>
            </div>
            <div class="card-body">
                <label>Nama Mitra</label>
                <input type="text" name="nama_mitra" class="form-control" value="{{ $pengabdian->mitra->nama }}" readonly>
                <br>
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" value="{{ $pengabdian->mitra->alamat }}" readonly>
                <br>
                <label>Periode Pengabdian</label>
                <input type="date" name="start_date" class="form-control" value="{{ $pengabdian->start_date }}" readonly>
                <br>
                <input type="date" name="end_date" class="form-control" value="{{ $pengabdian->end_date }}" readonly>
                <br>
            </div>
        </div>

        @if(Auth::user()-> role != 'siswa')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Siswa</h4>
            </div>
            <div class="card-body">
                <label>Nama Siswa</label>
                <input type="text" name="nama_siswa" class="form-control" value="{{ $pengabdian->siswa->user->name }}" readonly>
                <br>
                <label>Kelas</label>
                <input type="text" name="kelas_siswa" class="form-control" value="{{ $pengabdian->siswa->kelas }}" readonly>
                <br>
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" value="{{ $pengabdian->siswa->jurusan }}" readonly>
                <br>
            </div>
        </div>

        @endif
        @if(Auth::user()->role == 'pembimbing')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Nilai</h4>
                <p class="text-sm text-danger">Nilai dapat diberikan jika periode pengabdian telah usai.</p>
            </div>
            <div class="card-body">
                <div>
                    <label class="form-label">Prediksi Nilai (AI) <small class="text-danger italic">Prediksi dapat dilakukan untuk nilai sementara</small></label>
                    <div class="input-group mb-3">
                        <input type="text" name="prediksi_nilai" class="form-control" value="" readonly>
                        <button class="btn btn-primary" type="button">Prediksi Nilai</button>
                        <button class="btn btn-success" type="button" disabled>Gunakan</button>
                    </div>
                </div>
                <br>
                <form action="{{ route('penilaian.store') }}" method="POST" id="form-penilaian">
                    @csrf
                    <input type="hidden" name="pengabdian_id" value="{{ $pengabdian->id }}">


                    <div>
                        <label>Nilai Dari Pembimbing</label>
                        <div class="input-group mb-3">
                            <input type="text" name="nilai" id="nilai_final" value="{{isset($penilaianExist) ? $penilaianExist->nilai : ''}}" class="form-control">
                        </div>
                    </div>

                    <div>
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan...">{{isset($penilaianExist) ? $penilaianExist->catatan : ''}}</textarea>
                    </div>

                    <br>
                    <button class="btn btn-primary" type="submit" id="btn-simpan">Simpan Penilaian</button>
                </form>
                <br>
            </div>
        </div>
        @elseif(isset($penilaianExist))
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Nilai</h4>
                <p class="text-sm text-danger">Nilai diberikan jika periode pengabdian telah usai.</p>
            </div>
            <div class="card-body">
                <div>
                    <label>Nilai Dari Pembimbing</label>
                    <div class="input-group mb-3">
                        <input type="text" name="nilai" id="nilai_final" value="{{isset($penilaianExist) ? $penilaianExist->nilai : ''}}" class="form-control" readonly>
                    </div>
                </div>

                <div>
                    <label>Catatan</label>
                    <textarea name="catatan" class="form-control" readonly rows="3" placeholder="Belum ada catatan">{{isset($penilaianExist) ? $penilaianExist->catatan : ''}}</textarea>
                </div>

                <br>
            </div>
        </div>
        @endif

        @if(Auth::user()->role == 'siswa')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Laporan Harian</h4>
            </div>
            <div class="card-body">
                <form action="{{route('laporan.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="pengabdian_id" value="{{$pengabdian->id}}">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="currentDate" class="form-control" readonly>
                    <br>
                    <label for="deskripsi">Jenis dan Uraian Pekerjaan / Kegiatan</label>
                    <textarea name="deskripsi" class="form-control " style="height: 200px; resize: none;" id="deskripsi">{{ $todayLaporan->deskripsi ?? '' }}</textarea>
                    <br>
                    <button class="btn btn-primary" type="submit">@if($todayLaporan) Edit @else Submit @endif</button>
                </form>
            </div>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari / Tanggal</th>
                            <th>Jenis dan Uraian Pekerjaan / Kegiatan</th>
                            <th>Paraf Pembimbing</th>
                            <th>Presensi</th>
                            <th>Keterangan Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i=0; $i < $interval ; $i++)
                            @php
                            $currentDate=$start->copy()->addDays($i)->format('Y-m-d');
                            $dayName = $hari[Carbon\Carbon::parse($currentDate)->format('l')];

                            $laporan = $laporanHarian[$currentDate] ?? null;
                            $deskripsi = $laporan ? $laporan['deskripsi'] : '-';
                            $status = $laporan ? $laporan['status'] : 'Laporan Kosong';
                            $keterangan = $laporan ? $laporan['keterangan'] : '';
                            $isApproved = $laporan ? $laporan['isApproved'] : false;
                            $presensiId = $laporan ? $laporan['presensi_id'] : '';

                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $dayName }}, {{ Carbon\Carbon::parse($currentDate)->format('d-m-Y') }}</td>

                                {{-- Deskripsi field (Disabled if no laporan harian) --}}
                                <td>
                                    @if (isset($deskripsi))
                                    {{ $deskripsi }}
                                    @else
                                    <span class="text-danger">Belum ada laporan</span>
                                    @endif
                                </td>

                                {{-- Switch for Approval --}}
                                @if(Auth::user()->role == 'pembimbing')
                                <td>
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input isApproved-switch"
                                            type="checkbox"
                                            name="isApproved"
                                            data-id="{{ $presensiId }}"
                                            {{ $isApproved ? 'checked' : '' }}
                                            {{ !$presensiId ? 'disabled' : '' }}>
                                        <label class="form-check-label">Diterima</label>
                                    </div>
                                </td>

                                {{-- Status Kehadiran Dropdown --}}
                                <td>
                                    <div class="dropdown">
                                        <a style="text-transform: capitalize" class="btn btn-primary dropdown-toggle {{ !$presensiId ? 'disabled' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                            {{ $status }}
                                        </a>
                                        <ul class="dropdown-menu status-dropdown" data-id="{{ $presensiId }}">
                                            <li><a class="dropdown-item" href="">Hadir</a></li>
                                            <li><a class="dropdown-item" href="">Sakit</a></li>
                                            <li><a class="dropdown-item" href="">Izin</a></li>
                                            <li><a class="dropdown-item" href="">Alpha</a></li>
                                        </ul>
                                    </div>
                                </td>

                                {{-- Deskripsi Input --}}
                                <td>
                                    <textarea
                                        class="form-control deskripsi-field"
                                        data-id="{{ $presensiId }}"
                                        rows="3"
                                        {{ !$presensiId ? 'disabled' : '' }}>{{ $laporan ? $laporan['keterangan'] : '' }}</textarea>
                                    <br>
                                    <button
                                        class="btn btn-primary save-deskripsi"
                                        data-id="{{ $presensiId }}"
                                        {{ !$presensiId ? 'disabled' : '' }}>
                                        Simpan
                                    </button>
                                </td>

                                @else
                                <td>

                                    <div class="btn {{ $isApproved ? 'btn-primary' : 'btn-danger' }}">
                                        {{ $isApproved ? 'Disetujui' : 'Belum Disetujui' }}
                                    </div>

                                </td>
                                <td>
                                    <div style="text-transform: capitalize;">{{ $status }}</div>
                                </td>
                                <td>
                                    <div>{{ $keterangan }}</div>
                                </td>

                                @endif
                            </tr>

                            @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <style>
        .custom-table {
            width: 100%;
            table-layout: fixed;
            /* Ensures consistent column width */
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            padding: 10px;
            vertical-align: middle;
        }

        table {
            border: 1px solid black;
            border-collapse: collapse;
        }


        .custom-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .custom-table tbody tr {
            height: 150px;
            /* Reduce height for the first two rows */
        }

        .custom-table th:first-child,
        .custom-table td:first-child {
            width: 50px;
            /* First column (No) narrower */
        }


        .custom-table th:nth-child(2),
        .custom-table td:nth-child(2) {
            width: 150px;
            /* Second column (Hari / Tanggal) not too wide */
        }

        .custom-table th:nth-child(3),
        .custom-table td:nth-child(3) {
            width: 700px;
            /* 3 column not too wide */
        }

        .custom-table th:nth-child(4),
        .custom-table td:nth-child(4) {
            width: 140px;
            /* 3 column not too wide */
        }



        .custom-table th:last-child,
        .custom-table td:last-child {
            width: 250px;
            /* Adjust width for last column */
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("currentDate").value = today;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to send AJAX requests
            function sendRequest(presensiId, data) {
                if (!presensiId) {
                    alert("Presensi tidak ditemukan.");
                    return;
                }

                fetch(`/presensi/${presensiId}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(
                            data
                        )
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Gagal memperbarui data.");
                        return response.json();
                    })
                    .then(() => alert("Berhasil diperbarui!"))
                    .catch((e) => alert("Gagal memperbarui data." + e));
            }

            // Update isApproved status
            document.querySelectorAll(".isApproved-switch").forEach(switchInput => {
                switchInput.addEventListener("change", function() {
                    let presensiId = this.dataset.id;
                    let isApproved = this.checked ? 1 : 0;

                    sendRequest(presensiId, {
                        isApproved
                    });
                });
            });

            // Update status kehadiran
            document.querySelectorAll(".status-dropdown .dropdown-item").forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();
                    let presensiId = this.closest(".status-dropdown").dataset.id;
                    let status = this.textContent;
                    let btn = this.closest(".dropdown").querySelector(".btn");

                    sendRequest(presensiId, {
                        status
                    });
                    btn.textContent = status; // Update button text
                });
            });

            // Save deskripsi
            document.querySelectorAll(".save-deskripsi").forEach(button => {
                button.addEventListener("click", function() {
                    let presensiId = this.dataset.id;
                    let deskripsi = this.closest("td").querySelector(".deskripsi-field").value;
                    console.log(deskripsi)
                    sendRequest(presensiId, {
                        deskripsi
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const predictBtn = document.querySelector('button.btn-primary[type="button"]');
            const prediksiInput = document.querySelector('input[name="prediksi_nilai"]');
            const gunakanBtn = document.querySelector('button.btn-success');
            const nilaiFinalInput = document.getElementById("nilai_final");

            predictBtn.addEventListener('click', function() {
                predictBtn.disabled = true;
                predictBtn.textContent = 'Memproses...';

                fetch(`/penilaian/predict/{{ $pengabdian->id }}`, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            pengabdian_id: '{{ $pengabdian->id }}'
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memproses prediksi.');
                        return response.json();
                    })
                    .then(data => {
                        if (data.predicted_nilai !== undefined) {
                            prediksiInput.value = data.predicted_nilai;
                            gunakanBtn.disabled = false;
                        } else {
                            alert('Prediksi gagal.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan.');
                    })
                    .finally(() => {
                        predictBtn.disabled = false;
                        predictBtn.textContent = 'Prediksi Nilai';
                    });
            });

            gunakanBtn.addEventListener("click", function() {
                nilaiFinalInput.value = prediksiInput.value;

            });
        });
    </script>

</x-app-layout>