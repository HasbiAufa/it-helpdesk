<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Helpdesk RS - Syahid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">  
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-hospital-user me-2"></i> IT Helpdesk Internal
            </a>
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm fw-bold shadow-sm">
                <i class="bi bi-box-arrow-left"></i> Keluar (Logout)
            </a>
        </div>
    </nav>
    
    <div class="container mt-4">      
        
        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
            <h4 class="fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>Dashboard Monitoring</h4>
            <div class="d-flex">
                <label class="col-form-label me-2 fw-bold text-muted small">Bulan:</label>
                <input type="text" id="monthPicker" name="filter" class="form-control form-control-sm bg-white" 
                    value="{{ $filter }}" style="width: 100px; cursor: pointer;" placeholder="Pilih Bulan..." readonly>
            </div>
        </div>
        
        {{-- KONTAINER CARD DASHBOARD (UDAH DIKASIH ID) --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Tiket Baru</h6>
                        <h2 class="fw-bold display-6" id="valTotalBaru">{{ $totalBaru }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Belum direspon</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-dark bg-warning mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Sedang Proses</h6>
                        <h2 class="fw-bold display-6" id="valTotalProses">{{ $totalProses }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-tools me-1"></i> Sedang dikerjakan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Selesai</h6>
                        <h2 class="fw-bold display-6" id="valTotalSelesai">{{ $totalSelesai }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-check-double me-1"></i> Sudah tuntas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Rata-rata Respon</h6>
                        <h2 class="fw-bold display-6" id="valAvgRespon">±{{ $avgResponseTime }}<span class="fs-6">mnt</span></h2>
                        <p class="card-text small mb-0"><i class="fas fa-stopwatch me-1"></i> Kesigapan Admin</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL REKAP KATEGORI --}}
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-table me-1"></i> Keterangan Jumlah Laporan per Kategori 
                (<span id="periodeTabelKategori">{{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }}</span>)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No.</th>
                                <th class="text-start">Kategori</th>
                                <th width="15%">Total</th>
                                <th width="15%" class="bg-success text-white">Tertangani</th>
                                <th width="15%" class="bg-danger text-white">Tidak Tertangani</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRekapKategori">
                            @php $sumTotal = 0; $sumTertangani = 0; $sumTidak = 0; @endphp
                            @foreach($rekapKategori as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}.</td>
                                    <td class="text-start fw-bold">{{ $data['nama'] }}</td>
                                    <td>{{ $data['total'] }}</td>
                                    <td class="fw-bold text-success">{{ $data['tertangani'] }}</td>
                                    <td class="fw-bold text-danger">{{ $data['tidak_tertangani'] }}</td>
                                </tr>
                                @php 
                                    $sumTotal += $data['total']; $sumTertangani += $data['tertangani']; $sumTidak += $data['tidak_tertangani'];
                                @endphp
                            @endforeach
                            <tr class="fw-bold table-secondary">
                                <td colspan="2" class="text-center">JUMLAH TOTAL</td>
                                <td>{{ $sumTotal }}</td>
                                <td>{{ $sumTertangani }}</td>
                                <td>{{ $sumTidak }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- FORM CATAT TIKET --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-pen me-1"></i> Catat Tiket Baru</div>
                    <div class="card-body p-3">
                        <form id="formCatatTiket" action="{{ route('ticket.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lokasi / Ruangan</label>
                                <input type="text" name="lokasi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Jaringan">Jaringan</option>
                                    <option value="Komputer">Komputer</option>
                                    <option value="Printer">Printer</option>
                                    <option value="Khanza">Khanza</option>
                                    <option value="Sistem">Sistem</option>
                                    <option value="Antrian">Antrian</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kendala</label>
                                <textarea name="kendala" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Simpan Log</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DAFTAR TIKET AJAX --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="fas fa-list me-1"></i> Daftar Tiket 
                        (<span id="periodeDaftarTiket">{{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }}</span>)
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="dataTableTickets" class="table table-striped table-hover mb-0 align-middle border" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Kategori</th>
                                        <th>Kendala</th>
                                        <th>Status & Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="containerModals"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>

    <script>
        $(document).ready(function() {
            
            // INISIASI DATATABLES + TANGKEP STATS DASHBOARD
            let table = $('#dataTableTickets').DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('ajax.tickets') }}",
                    type: "GET",
                    data: function(d) {
                        d.filter = $('#monthPicker').val();
                    },
                    dataSrc: function (json) {
                        // KEAJAIBAN AJAX ADA DI SINI: Ngerubah Card & Tabel Kategori
                        if(json.stats) {
                            $('#valTotalBaru').text(json.stats.totalBaru);
                            $('#valTotalProses').text(json.stats.totalProses);
                            $('#valTotalSelesai').text(json.stats.totalSelesai);
                            $('#valAvgRespon').html('±' + json.stats.avgRespon + '<span class="fs-6">mnt</span>');
                            $('#tbodyRekapKategori').html(json.stats.htmlKategori);
                            $('#containerModals').html(json.stats.htmlModals);
                        }
                        return json.data;
                    }
                },
                columns: [
                    { data: 'tanggal', name: 'tanggal', className: 'text-center' },
                    { data: 'lokasi', name: 'lokasi', className: 'fw-bold text-start' },
                    { data: 'kategori', name: 'kategori', className: 'text-center' },
                    { data: 'kendala', name: 'kendala' },
                    { data: 'action', name: 'action', className: 'text-center' }
                ]
            });

            // KALENDER BERUBAH -> TABEL, CARD, & JUDUL REFRESH
            flatpickr("#monthPicker", {
                locale: "id", 
                altInput: true,
                plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: "Y-m", altFormat: "F Y", theme: "light" })],
                onChange: function(selectedDates, dateStr, instance) {
                    
                    // --- IDE LU DITERAPIN DI SINI BRO! ---
                    // 1. Daftar nama bulan bahasa Indonesia
                    const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    
                    // 2. Tangkep nilai kalender (Misal: "2026-04")
                    let valKalender = $('#monthPicker').val();
                    let pecah = valKalender.split('-'); // Dipisah jadi ['2026', '04']
                    
                    let tahun = pecah[0];
                    let indeksBulan = parseInt(pecah[1]) - 1; // Dikurang 1 karena array mulai dari 0
                    
                    // 3. Bikin let judul ala lu
                    let judul = namaBulan[indeksBulan] + " " + tahun;
                    let judulBaru = namaBulan[indeksBulan] + " " + tahun;
                    
                    // 4. Tembak ke HTML
                    $('#periodeTabelKategori').text(judul);
                    $('#periodeDaftarTiket').text(judulBaru);
                    // -------------------------------------

                    // Refresh DataTables & Card dari AJAX
                    table.ajax.reload();
                }
            });

            // SUBMIT FORM TIKET BARU TANPA RELOAD
            $('#formCatatTiket').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'), method: $(this).attr('method'), data: $(this).serialize(),
                    success: function(response) {
                        $('#formCatatTiket')[0].reset();
                        Swal.fire({ icon: 'success', title: 'Mantap!', text: response.message, timer: 2000, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    },
                    error: function() { Swal.fire('Error!', 'Ada yang salah nih bro.', 'error'); }
                });
            });
            
            // SUBMIT TOMBOL STATUS (Tangani/Selesaikan) TANPA RELOAD
            $(document).on('submit', '.formUpdateStatus', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'), method: $(this).attr('method'), data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'Status Diperbarui', timer: 1500, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    }
                });
            });

        });
    </script>
</body>
</html>