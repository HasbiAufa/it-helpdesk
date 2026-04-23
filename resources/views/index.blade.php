<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Helpdesk RS - Syahid</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button { @apply rounded-lg border-0 transition-all !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { @apply bg-indigo-600 text-white shadow-md !important; }
        .dataTables_filter input { @apply border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 !important; }
        .dataTables_length select { @apply border-gray-200 rounded-lg text-sm !important; }

    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900">  
    <nav class="bg-blue-600 shadow-md py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <a class="flex items-center text-white font-extrabold text-xl tracking-tight" href="#">
                <i class="fas fa-hospital-user mr-3"></i> 
                <span>IT HELPDESK</span>
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-blue-100 hidden sm:block font-medium">Selamat bekerja, Admin!</span>
                <a href="{{ route('logout') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-red-500 hover:bg-red-800 transition-colors shadow-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">      
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-indigo-600"></i> Monitoring Dashboard
                </h1>
                <p class="text-sm text-gray-500 mt-1">Pantau dan kelola tiket dukungan IT secara real-time.</p>
            </div>
            <div class="flex items-center bg-white border border-gray-200 rounded-lg px-3 py-1.5 shadow-sm">
                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                <input type="text" id="monthPicker" name="filter" class="text-sm font-semibold text-gray-700 bg-transparent focus:outline-none cursor-pointer w-32" 
                    value="{{ $filter }}" placeholder="Pilih Bulan..." readonly>
            </div>
        </div>
        
        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <span class="p-2 bg-red-50 text-red-600 rounded-lg">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-4">Tiket Baru</h3>
                    <p class="text-3xl font-black text-gray-900 mt-1" id="valTotalBaru">{{ $totalBaru }}</p>
                </div>
                <div class="mt-4 flex items-center text-xs text-red-600 font-medium bg-red-50 px-2 py-1 rounded-full w-fit">
                    <i class="fas fa-clock mr-1"></i> Menunggu Respon
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <span class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <i class="fas fa-spinner text-lg"></i>
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-4">Dalam Proses</h3>
                    <p class="text-3xl font-black text-gray-900 mt-1" id="valTotalProses">{{ $totalProses }}</p>
                </div>
                <div class="mt-4 flex items-center text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-full w-fit">
                    <i class="fas fa-tools mr-1"></i> Sedang Ditangani
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <i class="fas fa-check-circle text-lg"></i>
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-4">Selesai</h3>
                    <p class="text-3xl font-black text-gray-900 mt-1" id="valTotalSelesai">{{ $totalSelesai }}</p>
                </div>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-1 rounded-full w-fit">
                    <i class="fas fa-check-double mr-1"></i> Tuntas Dikerjakan
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <i class="fas fa-stopwatch text-lg"></i>
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-4">Rata-rata Respon</h3>
                    <p class="text-3xl font-black text-gray-900 mt-1" id="valAvgRespon">±{{ $avgResponseTime }}<span class="text-sm font-normal text-gray-500 ml-1">mnt</span></p>
                </div>
                <div class="mt-4 flex items-center text-xs text-indigo-600 font-medium bg-indigo-50 px-2 py-1 rounded-full w-fit">
                    <i class="fas fa-bolt mr-1"></i> Kecepatan Penanganan
                </div>
            </div>
        </div>

        {{-- REKAP TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-800 flex items-center">
                    <i class="fas fa-table mr-2 text-indigo-500"></i>
                    Statistik Per Kategori
                    <span class="ml-2 text-gray-400 font-normal" id="periodeTabelKategori">({{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }})</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider w-16">No.</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider text-left">Kategori</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider bg-emerald-50/50 text-emerald-700">Tertangani</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider bg-red-50/50 text-red-700">Terbengkalai</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRekapKategori">
                        @php $sumTotal = 0; $sumTertangani = 0; $sumTidak = 0; @endphp
                        @foreach($rekapKategori as $index => $data)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $index + 1 }}.</td>
                                <td class="py-4 px-6 text-sm font-semibold text-gray-900 text-left">{{ $data['nama'] }}</td>
                                <td class="py-4 px-6 text-sm text-gray-600">{{ $data['total'] }}</td>
                                <td class="py-4 px-6 text-sm font-bold text-emerald-600">{{ $data['tertangani'] }}</td>
                                <td class="py-4 px-6 text-sm font-bold text-red-600">{{ $data['tidak_tertangani'] }}</td>
                            </tr>
                            @php 
                                $sumTotal += $data['total']; $sumTertangani += $data['tertangani']; $sumTidak += $data['tidak_tertangani'];
                            @endphp
                        @endforeach
                        <tr class="bg-gray-50 font-bold border-t-2 border-gray-100">
                            <td colspan="2" class="py-4 px-6 text-sm text-gray-900 text-center">JUMLAH TOTAL</td>
                            <td class="py-4 px-6 text-sm text-gray-900">{{ $sumTotal }}</td>
                            <td class="py-4 px-6 text-sm text-emerald-600">{{ $sumTertangani }}</td>
                            <td class="py-4 px-6 text-sm text-red-600">{{ $sumTidak }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- FORM --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center">
                            <i class="fas fa-pen-nib mr-2 text-indigo-500"></i> Catat Tiket Baru
                        </h3>
                    </div>
                    <div class="p-6">
                        <form id="formCatatTiket" action="{{ route('ticket.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi / Ruangan</label>
                                <input type="text" name="lokasi" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Contoh: Poli Umum" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori</label>
                                <select name="kategori" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Jaringan">🌐 Jaringan</option>
                                    <option value="Komputer">💻 Komputer</option>
                                    <option value="Printer">🖨️ Printer</option>
                                    <option value="Khanza">🏥 Khanza</option>
                                    <option value="Sistem">⚙️ Sistem</option>
                                    <option value="Antrian">🔢 Antrian</option>
                                    <option value="Lain-lain">❓ Lain-lain</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Detail Kendala</label>
                                <textarea name="kendala" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" rows="4" placeholder="Jelaskan masalah..." required></textarea>
                            </div>
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Simpan Log Tiket
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LIST --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center">
                            <i class="fas fa-list-ul mr-2 text-indigo-500"></i> Daftar Laporan Tiket
                            <span class="ml-2 text-gray-400 font-normal" id="periodeDaftarTiket">({{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }})</span>
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table id="dataTableTickets" class="w-full text-left border-collapse" style="width:100%">
                                <thead>
                                    <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        <th class="pb-3 text-center">Waktu</th>
                                        <th class="pb-3">Lokasi</th>
                                        <th class="pb-3 text-center">Kategori</th>
                                        <th class="pb-3">Kendala</th>
                                        <th class="pb-3 text-center">Status & Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-50"></tbody>
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
            
            // 1. VARIABEL "INGATAN" (Naro di sini biar nggak hilang pas refresh tabel)
            let lastCount = null;

            // 2. INISIASI DATATABLES + TANGKEP STATS DASHBOARD
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

                            // --- LOGIKA REAL-TIME NOTIF ---
                            // Cek apakah jumlah tiket sekarang lebih banyak dari sebelumnya
                            if (lastCount !== null && json.data.length > lastCount) {
                                Swal.fire({
                                    title: 'Tiket Baru!',
                                    text: 'Ada laporan baru!',
                                    icon: 'info',
                                    toast: true,
                                    position: 'top-end', // Di pojok kanan atas biar nggak ganggu
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true
                                });
                            }
                            // Update angka terakhir buat perbandingan berikutnya
                            lastCount = json.data.length;
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

            // 3. TIMER AUTO-REFRESH (POLLING)
            // Cek data ke server tiap 15 detik secara background
            setInterval(function() {
                // reload(null, false) artinya: refresh data tapi posisi halaman user nggak berubah
                table.ajax.reload(null, false); 
            }, 10000);

            // 4. KALENDER BERUBAH -> TABEL, CARD, & JUDUL REFRESH
            flatpickr("#monthPicker", {
                locale: "id", 
                altInput: true,
                plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: "Y-m", altFormat: "F Y", theme: "light" })],
                onChange: function(selectedDates, dateStr, instance) {
                    const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    let pecah = dateStr.split('-'); 
                    let tahun = pecah[0]; 
                    let indeksBulan = parseInt(pecah[1]) - 1; 
                    
                    let judulBaru = namaBulan[indeksBulan] + " " + tahun;
                    
                    $('#periodeTabelKategori').text(judulBaru);
                    $('#periodeDaftarTiket').text(judulBaru);

                    // Reset lastCount pas ganti bulan biar nggak salah deteksi notif tiket baru bulan lalu
                    lastCount = null;
                    table.ajax.reload();
                }
            });

            // 5. SUBMIT FORM TIKET BARU TANPA RELOAD
            $('#formCatatTiket').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'), 
                    method: $(this).attr('method'), 
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formCatatTiket')[0].reset();
                        Swal.fire({ icon: 'success', title: 'Mantap!', text: response.message, timer: 2000, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    },
                    error: function() { Swal.fire('Error!', 'Ada yang salah nih bro.', 'error'); }
                });
            });
            
            // 6. SUBMIT TOMBOL STATUS (Tangani/Selesaikan) TANPA RELOAD
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

            // 7. SUBMIT TOMBOL HAPUS (Pake SweetAlert) TANPA RELOAD
            $(document).on('submit', '.formDeleteTicket', function(e) {
                e.preventDefault();
                let form = $(this);

                Swal.fire({
                    title: 'Yakin mau dihapus?',
                    text: "Data tiket ini bakal hilang permanen lho!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            method: form.attr('method'),
                            data: form.serialize(),
                            success: function(response) {
                                form.closest('.modal').modal('hide');
                                Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                                table.ajax.reload(null, false);
                            },
                            error: function() { Swal.fire('Error!', 'Gagal menghapus tiket bro.', 'error'); }
                        });
                    }
                });
            });

        });
    </script>
</body>
</html>