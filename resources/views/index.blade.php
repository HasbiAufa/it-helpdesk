<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Helpdesk RS - Syahid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">  
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="http://it-helpdesk.test">
                <i class="fas fa-hospital-user me-2"></i> IT Helpdesk Internal
            </a>
            <span class="text-white text-sm">RS Syarif Hidayatullah</span>
        </div>
    </nav>
    
    <div class="container mt-4">      
        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
            <h4 class="fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>Dashboard Monitoring</h4>
            <form action="{{ route('home') }}" method="GET" id="filterForm" class="d-flex">
                <label class="col-form-label me-2 fw-bold text-muted small">Bulan:</label>
                
                <input type="text" id="monthPicker" name="filter" class="form-control form-control-sm bg-white" 
                    value="{{ $filter }}" style="width: 100px; cursor: pointer;" placeholder="Pilih Bulan..." readonly>
            </form>
        </div>
        
        {{-- KONTAINER TOTAL TIKET YANG BELUM DIRESPON --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Tiket Baru</h6>
                        <h2 class="fw-bold display-6">{{ $totalBaru }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Belum direspon</p>
                    </div>
                </div>
            </div>

            {{-- KONTAINER TOTAL TIKET YANG SEDANG DIPROSES --}}
            <div class="col-md-3">
                <div class="card text-dark bg-warning mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Sedang Proses</h6>
                        <h2 class="fw-bold display-6">{{ $totalProses }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-tools me-1"></i> Sedang dikerjakan</p>
                    </div>
                </div>
            </div>

            {{-- KONTAINER TOTAL TIKET YANG SELESAI --}}
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Selesai</h6>
                        <h2 class="fw-bold display-6">{{ $totalSelesai }}</h2>
                        <p class="card-text small mb-0"><i class="fas fa-check-double me-1"></i> Sudah tuntas</p>
                    </div>
                </div>
            </div>

            {{-- KONTAINER RATA-RATA TIKET DIRESPON --}}
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase small opacity-75">Rata-rata Respon</h6>
                        <h2 class="fw-bold display-6">±{{ $avgResponseTime }}<span class="fs-6">mnt</span></h2>
                        <p class="card-text small mb-0"><i class="fas fa-stopwatch me-1"></i> Kesigapan Admin</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL REKAP KATEGORI --}}
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-table me-1"></i> Keterangan Jumlah Laporan per Kategori ({{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }})
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
                        
                        <tbody>
                            @php 
                                $sumTotal = 0; 
                                $sumTertangani = 0; 
                                $sumTidak = 0; 
                            @endphp
                            
                            @foreach($rekapKategori as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}.</td>
                                    <td class="text-start fw-bold">{{ $data['nama'] }}</td>
                                    <td>{{ $data['total'] }}</td>
                                    <td class="fw-bold text-success">{{ $data['tertangani'] }}</td>
                                    <td class="fw-bold text-danger">{{ $data['tidak_tertangani'] }}</td>
                                </tr>

                                @php 
                                    $sumTotal += $data['total'];
                                    $sumTertangani += $data['tertangani'];
                                    $sumTidak += $data['tidak_tertangani'];
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
            {{-- ALERTY SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            {{-- KONTAINER INPUT TIKET BARU --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-pen me-1"></i> Catat Tiket Baru
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('ticket.store') }}" method="POST">
                            @csrf
                            
                            {{-- KOLOM LOKASI --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lokasi / Ruangan</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: IGD / Pendaftaran" required>
                            </div>

                            {{-- KOLOM KATEGORI --}}
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

                            {{-- KOLOM KENDALA --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kendala</label>
                                <textarea name="kendala" class="form-control" rows="3" placeholder="Tuliskan keluhan user di sini..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> Simpan Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TABEL DAFTAR TIKET BULAN INI DAN FUNCTION EXPORT --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-list me-1"></i> Daftar Tiket ({{ \Carbon\Carbon::parse($filter . '-01')->translatedFormat('F Y') }})</span>
                        <a href="{{ route('ticket.export', ['filter' => $filter]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Export Laporan
                        </a>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0" style="font-size: 0.9rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Kategori</th>
                                        <th>Kendala</th>
                                        <th>Status</th>
                                        <th>Edit</th> 
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    
                                    <tr>
                                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="fw-bold">{{ $ticket->lokasi }}</td>
                                        
                                        <td>
                                            <span class="badge bg-secondary">{{ $ticket->kategori }}</span>
                                        </td>
                                        
                                        <td>{{ Str::limit($ticket->kendala, 50) }}</td>
                                        
                                        {{-- FITUR UPDATE STATUS --}}
                                        <td>
                                            <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="d-flex align-items-center gap-2">
                                                
                                                    @if($ticket->status == 'Baru')
                                                        <span class="badge bg-danger">🔴 Baru</span>
                                                    @elseif($ticket->status == 'Proses')
                                                        <span class="badge bg-warning text-dark">⏳ Proses</span>
                                                    @else
                                                        <span class="badge bg-success">✅ Selesai</span>
                                                    @endif

                                                    @if($ticket->status != 'Selesai')
                                                        @if($ticket->status == 'Baru')
                                                            <input type="hidden" name="status" value="Proses">
                                                            <button type="submit" class="btn btn-sm btn-outline-warning fw-bold" title="Klik untuk mulai menangani">
                                                                <i class="fas fa-tools"></i> Tangani
                                                            </button>
                                                        @elseif($ticket->status == 'Proses')
                                                            <input type="hidden" name="status" value="Selesai">
                                                            <button type="submit" class="btn btn-sm btn-outline-success fw-bold" title="Klik jika sudah beres">
                                                                <i class="fas fa-check"></i> Selesaikan
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </form>
                                            
                                            {{-- NAMPILIN WAKTU RESPON DAN SELESAI DI STATUS --}}
                                            <div style="font-size: 10px; color: gray; margin-top: 4px;">
                                                @if($ticket->waktu_respon)
                                                    <div>Respon: {{ \Carbon\Carbon::parse($ticket->waktu_respon)->format('H:i') }}</div>
                                                    @endif
                                                    @if($ticket->waktu_selesai)
                                                    <div>Selesai: {{ \Carbon\Carbon::parse($ticket->waktu_selesai)->format('H:i') }}</div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- TOMBOL EDIT --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light border" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEdit{{ $ticket->id }}">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- POP UP EDIT --}}
                                    <div class="modal fade" id="modalEdit{{ $ticket->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold text-primary">
                                                        <i class="fas fa-edit me-2"></i>Edit & Revisi Tiket
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <form id="formUpdate{{ $ticket->id }}" action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <div class="p-3 bg-white border rounded shadow-sm">
                                                                    <h6 class="fw-bold border-bottom pb-2 mb-3">📝 Data Laporan</h6>

                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label small text-muted fw-bold">Lokasi / Ruangan</label>
                                                                            <input type="text" name="lokasi" class="form-control"
                                                                                value="{{ $ticket->lokasi }}" placeholder="Contoh: Poli Gigi">
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label class="form-label small text-muted fw-bold">Kategori</label>
                                                                            <select name="kategori" class="form-select" required>
                                                                                <option value="Jaringan" {{ $ticket->kategori == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                                                                <option value="Komputer" {{ $ticket->kategori == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                                                                                <option value="Printer" {{ $ticket->kategori == 'Printer' ? 'selected' : '' }}>Printer</option>
                                                                                <option value="Khanza" {{ $ticket->kategori == 'Khanza' ? 'selected' : '' }}>Khanza</option>
                                                                                <option value="Antrian" {{ $ticket->kategori == 'Antrian' ? 'selected' : '' }}>Antrian</option>
                                                                                <option value="Lain-lain" {{ $ticket->kategori == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <label class="form-label small text-muted fw-bold">Kendala</label>
                                                                            <input type="text" name="kendala" class="form-control" value="{{ $ticket->kendala }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="p-3 bg-light border rounded">
                                                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                                                        <i class="fas fa-history me-1"></i> Timeline Waktu (Susulan)
                                                                    </h6>

                                                                    <div class="alert alert-warning py-2 px-3 small mb-3">
                                                                        <i class="fas fa-info-circle me-1"></i>
                                                                        Klik ikon kalender untuk memilih, atau <b>ketik angka jam</b> secara manual.
                                                                    </div>

                                                                    <div class="row g-3">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small fw-bold text-secondary">1. Laporan Masuk</label>
                                                                            <input type="datetime-local" name="created_at" class="form-control" value="{{ $ticket->created_at->format('Y-m-d\TH:i') }}">
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="form-label small fw-bold text-secondary">2. Mulai Dikerjakan</label>
                                                                            <input type="datetime-local" name="waktu_respon" class="form-control" value="{{ $ticket->waktu_respon ? \Carbon\Carbon::parse($ticket->waktu_respon)->format('Y-m-d\TH:i') : '' }}">
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="form-label small fw-bold text-secondary">3. Selesai Dikerjakan</label>
                                                                            <input type="datetime-local" name="waktu_selesai" class="form-control" value="{{ $ticket->waktu_selesai ? \Carbon\Carbon::parse($ticket->waktu_selesai)->format('Y-m-d\TH:i') : '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="modal-footer bg-light justify-content-between">
                                                    <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('⚠️ Yakin mau menghapus tiket ini permanen?');">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>

                                                    <div>
                                                        <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Batal</button>
                                                        
                                                        <button type="button" class="btn btn-primary fw-bold" onclick="document.getElementById('formUpdate{{ $ticket->id }}').submit()">
                                                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KALO DAFTAR TIKET BULANAN MASIH KOSONG --}}
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-ticket fa-2x mb-2"></i><br>
                                                Belum ada tiket masuk hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- SCRIPT RELOAD PAGE --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var alertElement = document.querySelector('.alert');

            if (alertElement) {
                alertElement.scrollIntoView({behavior: 'smooth', block: 'center'});
                
                setTimeout(function() {
                    var bsAlert = new bootstrap.Alert(alertElement);
                    bsAlert.close();
                }, 5000);
            }
        });
    </script>

    {{-- PLUGIN FLATPICKR (KALENDER) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#monthPicker", {
                locale: "id",
                altInput: true,
                
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        dateFormat: "Y-m",
                        altFormat: "F Y",
                        theme: "light"
                    })
                ],
                
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('filterForm').submit();
                }
            });
        });
    </script>
</body>
</html>