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
                                        <input type="text" name="lokasi" class="form-control" value="{{ $ticket->lokasi }}" placeholder="Contoh: Poli Gigi">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-muted fw-bold">Kategori</label>
                                        <select name="kategori" class="form-select" required>
                                            <option value="Jaringan" {{ $ticket->kategori == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                            <option value="Komputer" {{ $ticket->kategori == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                                            <option value="Printer" {{ $ticket->kategori == 'Printer' ? 'selected' : '' }}>Printer</option>
                                            <option value="Khanza" {{ $ticket->kategori == 'Khanza' ? 'selected' : '' }}>Khanza</option>
                                            <option value="Sistem" {{ $ticket->kategori == 'Sistem' ? 'selected' : '' }}>Sistem</option>
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