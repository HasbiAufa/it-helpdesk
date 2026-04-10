<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Laporan IT - RS Syahid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Biar background belakangnya abu-abu muda, jadi kotak form-nya lebih nonjol */
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>
    
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        
        <div class="col-12 col-md-8 col-lg-5 mb-4"> 
            
            <div class="card shadow border-0 rounded-4"> <div class="card-header bg-white fw-bold py-3 text-center text-primary fs-5 border-0 rounded-top-4">
                    <i class="fas fa-headset me-2"></i> Form Pelaporan IT
                </div>
                
                <div class="card-body p-4"> <form action="{{ route('ticket.store') }}" method="POST">
                        @csrf
                        
                        {{-- KOLOM LOKASI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Lokasi / Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control form-control-lg bg-light" placeholder="Contoh: IGD / Pendaftaran" required autofocus>
                        </div>

                        {{-- KOLOM KATEGORI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Kategori Kendala <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select form-select-lg bg-light" required>
                                <option value="">-- Pilih Salah Satu --</option>
                                <option value="Jaringan">🌐 Jaringan / Internet</option>
                                <option value="Komputer">💻 Komputer / PC</option>
                                <option value="Printer">🖨️ Printer</option>
                                <option value="Khanza">🏥 SIMRS Khanza</option>
                                <option value="Sistem">⚙️ Sistem Lainnya</option>
                                <option value="Antrian">🔢 Mesin Antrian</option>
                                <option value="Lain-lain">❓ Lain-lain</option>
                            </select>
                        </div>

                        {{-- KOLOM KENDALA --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Detail Kendala <span class="text-danger">*</span></label>
                            <textarea name="kendala" class="form-control bg-light" rows="4" placeholder="Jelaskan detail masalah yang dialami di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                        </button>
                    </form>
                </div>

            </div>
            
            <div class="text-center mt-3 text-muted small">
                Unit IT RS Syarif Hidayatullah &copy; 2026
            </div>
            
        </div>
    </div>

</body>
</html>