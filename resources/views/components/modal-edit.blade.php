<div class="modal fade" id="modalEdit{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h5 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-3 text-indigo-600"></i> Edit & Revisi Tiket
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="modal-body p-6">
                <form id="formUpdate{{ $ticket->id }}" action="{{ route('ticket.update', $ticket->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-5 border border-gray-100 rounded-xl space-y-4">
                        <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                            <i class="fas fa-file-alt mr-2 text-indigo-400"></i> Detail Laporan
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Lokasi / Ruangan</label>
                                <input type="text" name="lokasi" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" value="{{ $ticket->lokasi }}">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
                                <select name="kategori" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" required>
                                    <option value="Jaringan" {{ $ticket->kategori == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                    <option value="Komputer" {{ $ticket->kategori == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                                    <option value="Printer" {{ $ticket->kategori == 'Printer' ? 'selected' : '' }}>Printer</option>
                                    <option value="Khanza" {{ $ticket->kategori == 'Khanza' ? 'selected' : '' }}>Khanza</option>
                                    <option value="Sistem" {{ $ticket->kategori == 'Sistem' ? 'selected' : '' }}>Sistem</option>
                                    <option value="Antrian" {{ $ticket->kategori == 'Antrian' ? 'selected' : '' }}>Antrian</option>
                                    <option value="Lain-lain" {{ $ticket->kategori == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Kendala</label>
                                <textarea name="kendala" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" rows="2">{{ $ticket->kendala }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-50/50 p-5 border border-indigo-100 rounded-xl space-y-4">
                        <h6 class="text-xs font-bold text-indigo-600 uppercase tracking-wider flex items-center">
                            <i class="fas fa-history mr-2"></i> Timeline Progres
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase mb-1">1. Laporan Masuk</label>
                                <input type="datetime-local" name="created_at" class="w-full px-3 py-2 bg-white border border-indigo-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 transition-all" value="{{ $ticket->created_at->format('Y-m-d\TH:i') }}">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase mb-1">2. Mulai Respon</label>
                                <input type="datetime-local" name="waktu_respon" class="w-full px-3 py-2 bg-white border border-indigo-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 transition-all" value="{{ $ticket->waktu_respon ? \Carbon\Carbon::parse($ticket->waktu_respon)->format('Y-m-d\TH:i') : '' }}">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase mb-1">3. Waktu Selesai</label>
                                <input type="datetime-local" name="waktu_selesai" class="w-full px-3 py-2 bg-white border border-indigo-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 transition-all" value="{{ $ticket->waktu_selesai ? \Carbon\Carbon::parse($ticket->waktu_selesai)->format('Y-m-d\TH:i') : '' }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" class="formDeleteTicket">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center text-sm font-bold text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Tiket
                    </button>
                </form>

                <div class="flex space-x-3">
                    <button type="button" class="px-5 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all flex items-center" onclick="document.getElementById('formUpdate{{ $ticket->id }}').submit()">
                        <i class="fas fa-check-circle mr-2"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>