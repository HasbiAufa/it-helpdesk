<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\TicketExport;
use Illuminate\Http\Request;
use App\Models\Ticket; // buat controller ngenalin tabel ticket
use Maatwebsite\Excel\Facades\Excel;

class TicketCotroller extends Controller
{
    public function index(Request $request)
    {
        // FILTER WAKTU
        $defaultBulan = Carbon::now()->format('Y-m');
        $filter = $request->get('filter', $defaultBulan);

        if(empty($filter)){
            $filter = $defaultBulan;
        }

        $parts = explode('-', $filter);
        $tahun = $parts[0];
        $bulan = $parts[1];

        $query = Ticket::query();
        $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);

        $tickets = $query->orderBy('created_at', 'desc')->get();

        // STATUS DASHBOARD (ngikut filter)
        $totalBaru = $tickets->where('status', 'Baru')->count();
        $totalProses = $tickets->where('status', 'Proses')->count();
        $totalSelesai = $tickets->where('status', 'Selesai')->count();

        // REKAP KENDALA
        $ListKategori = ['Jaringan', 'Komputer', 'Printer', 'Khanza', 'Sistem', 'Antrian', 'Lain-lain'];
        $rekapKategori = [];

        foreach($ListKategori as $kat){
            $dataKategori = $tickets->where('kategori', $kat);

            $rekapKategori[] = [
                'nama' => $kat,
                'total' => $dataKategori->count(),
                'tertangani' => $dataKategori->where('status', 'Selesai')->count(),
                'tidak_tertangani' => $dataKategori->whereIn('status', ['Baru', 'Proses'])->count(),
            ];
        }

        // HITUNG RATA2 RESPON TIME
        $respondedTickets = $tickets->where('waktu_respon', '!=', null);
        $avgResponseTime = 0;

        if($respondedTickets->count() > 0){
            $totalSelisihMenit = $respondedTickets->reduce(function ($carry, $ticket){
                $start = Carbon::parse($ticket->created_at);
                $respon = Carbon::parse($ticket->waktu_respon);

                return $carry + $start->floatDiffInMinutes($respon);
            }, 0);

            // rata rata
            $avgResponseTime = round($totalSelisihMenit / $respondedTickets->count(), 1);
        }

        // DATA PIE CHART
        $dataPie = collect($rekapKategori)->pluck('total');

        return view('index', compact(
            'tickets',
            'filter',
            'totalBaru', 'totalProses', 'totalSelesai',
            'rekapKategori',
            'avgResponseTime',
            'dataPie'
        ));

        // $tickets = Ticket::latest()->get(); //ngambil tiket dan ngurutin dari yang paling baru 
        // return view('index', compact('tickets')); //nampilin view 
    }

    public function create()
    {
        return view('form-public');
    }

    public function store(Request $request) //function nyimpen data 
    {
        $request->validate([ //validasi kalo si input ini gaboleh kosong
            'lokasi' => 'required',
            'kategori' => 'required',
            'kendala' => 'required',
        ]);
        Ticket::create([ //bikin tiket terus disimpen di database
            'lokasi' => $request->lokasi, //3 ke bawah ini hasil input dari user
            'kategori' => $request->kategori,
            'kendala' => $request->kendala,
            'status' => 'Baru', // otomatis statusnya jadi baru ketika tiker baru aja terkirim
        ]);
        // Kalau yang ngirim request itu AJAX (dari form dashboard admin), bales pake JSON
        if($request->ajax()){
            return response()->json(['message' => 'Laporan berhasil dicatat!']);
        }
        
        // Kalau yang ngirim request itu dari form halaman publik biasa, tetep pake redirect
        return redirect()->back()->with('success', 'Laporan berhasil dicatat!');
    }

    public function export(Request $request)
    {
        $defaultBulan = Carbon::now()->format('Y-m');
        $filter = $request->get('filter', $defaultBulan);
        return Excel::download(new TicketExport($filter), 'laporan-helpdesk.xlsx');
    }
    
    public function update(Request $request, $id) //fungsi buat update status
    {
        $ticket = Ticket::findOrFail($id);
        if($request->has('lokasi') || $request->has('kategori') || $request->has('kendala')){
            $ticket->lokasi = $request->lokasi;
            $ticket->kategori = $request->kategori;
            $ticket->kendala = $request->kendala;

            if($request->filled('created_at')){
                $ticket->created_at = $request->created_at;
            }

            if($request->filled('waktu_respon')){
                $ticket->waktu_respon = $request->waktu_respon;
                if($ticket->status == 'Baru'){
                    $ticket->status = 'Proses';
                }
            }

            if($request->filled('waktu_selesai')){
                $ticket->waktu_selesai = $request->waktu_selesai;
                $ticket->status = 'Selesai';
            }

            $ticket->save();
            return redirect()->back()->with('success', 'Data tiket berhasil direvisi!');
        }

        $statusBaru = $request->status;
        $dataUpdate = ['status' => $statusBaru];

        if($statusBaru == 'Proses' && $ticket->waktu_respon == null){
            $dataUpdate['waktu_respon'] = now();
        }

        if($statusBaru == 'Selesai'){
            $dataUpdate['waktu_selesai'] = now();
            if($ticket->waktu_respon == null){
                $dataUpdate['waktu_respon'] = now();
            }
        }

        $ticket->update($dataUpdate);
        return redirect()->back()->with('success', 'Status diperbarui!');
    }

    // fungsi hapus data
    public function destroy(Request $request, $id){
        $ticket = Ticket::findOrFail($id); // nyari data
        $ticket->delete(); // hapus

        // Kalo requestnya dari AJAX (SweetAlert), kirim respon JSON
        if($request->ajax()){
            return response()->json(['message' => 'Tiket Berhasil Dihapus Permanen!']);
        }

        return redirect()->back()->with('success', 'Tiket Berhasil Dihapus!');
    }

    // Fungsi khusus buat ngasih data JSON ke DataTables
    // FUNGSI AJAX FULL DASHBOARD
    public function getAjaxTickets(Request $request)
    {
        $defaultBulan = \Carbon\Carbon::now()->format('Y-m');
        $filter = $request->get('filter', $defaultBulan);
        
        $parts = explode('-', $filter);
        $tahun = $parts[0];
        $bulan = $parts[1];

        // 1. TARIK DATA TIKET
        $tickets = Ticket::whereYear('created_at', $tahun)
                         ->whereMonth('created_at', $bulan)
                         ->orderBy('created_at', 'desc')
                         ->get();

        // 2. HITUNG STATISTIK CARD DASHBOARD
        $totalBaru = $tickets->where('status', 'Baru')->count();
        $totalProses = $tickets->where('status', 'Proses')->count();
        $totalSelesai = $tickets->where('status', 'Selesai')->count();

        $respondedTickets = $tickets->where('waktu_respon', '!=', null);
        $avgResponseTime = 0;
        if($respondedTickets->count() > 0){
            $totalSelisihMenit = $respondedTickets->reduce(function ($carry, $ticket){
                $start = \Carbon\Carbon::parse($ticket->created_at);
                $respon = \Carbon\Carbon::parse($ticket->waktu_respon);
                return $carry + $start->floatDiffInMinutes($respon);
            }, 0);
            $avgResponseTime = round($totalSelisihMenit / $respondedTickets->count(), 1);
        }

        // 3. HITUNG & RENDER HTML UNTUK TABEL REKAP KATEGORI
        $ListKategori = ['Jaringan', 'Komputer', 'Printer', 'Khanza', 'Antrian', 'Lain-lain'];
        $rekapKategoriHtml = '';
        $sumTotal = 0; $sumTertangani = 0; $sumTidak = 0;

        foreach($ListKategori as $index => $kat){
            $dataKategori = $tickets->where('kategori', $kat);
            $tot = $dataKategori->count();
            $ter = $dataKategori->where('status', 'Selesai')->count();
            $tdk = $dataKategori->whereIn('status', ['Baru', 'Proses'])->count();

            $sumTotal += $tot; $sumTertangani += $ter; $sumTidak += $tdk;

            $rekapKategoriHtml .= '
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 text-sm text-gray-500">'.($index + 1).'.</td>
                    <td class="py-3 px-4 text-sm font-semibold text-gray-900 text-start">'.$kat.'</td>
                    <td class="py-3 px-4 text-sm text-gray-600">'.$tot.'</td>
                    <td class="py-3 px-4 text-sm font-bold text-emerald-600">'.$ter.'</td>
                    <td class="py-3 px-4 text-sm font-bold text-red-600">'.$tdk.'</td>
                </tr>';
        }
        $rekapKategoriHtml .= '
            <tr class="bg-gray-50 font-bold">
                <td colspan="2" class="py-3 px-4 text-sm text-gray-900 text-center">JUMLAH TOTAL</td>
                <td class="py-3 px-4 text-sm text-gray-900">'.$sumTotal.'</td>
                <td class="py-3 px-4 text-sm text-emerald-600">'.$sumTertangani.'</td>
                <td class="py-3 px-4 text-sm text-red-600">'.$sumTidak.'</td>
            </tr>';

        // 4. FORMAT DATA UNTUK DATATABLES DAN MODAL EDIT
        $data = [];
        $modalsHtml = ''; // Kita kirim kodingan HTML pop up edit lewat sini biar ga error

        foreach ($tickets as $ticket) {
            // Logika Badge Status
            $badgeStatus = ''; $btnAction = '';
            
            if ($ticket->status == 'Baru') {
                $badgeStatus = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴 Baru</span>';
                $btnAction = '
                <form action="'.route("ticket.update", $ticket->id).'" method="POST" class="inline formUpdateStatus">
                    '.csrf_field().' '.method_field("PUT").'
                    <input type="hidden" name="status" value="Proses">
                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-amber-300 shadow-sm text-xs font-medium rounded text-amber-700 bg-white hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors"><i class="fas fa-tools mr-1"></i> Tangani</button>
                </form>';
            } elseif ($ticket->status == 'Proses') {
                $badgeStatus = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">⏳ Proses</span>';
                $btnAction = '
                <form action="'.route("ticket.update", $ticket->id).'" method="POST" class="inline formUpdateStatus">
                    '.csrf_field().' '.method_field("PUT").'
                    <input type="hidden" name="status" value="Selesai">
                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-emerald-300 shadow-sm text-xs font-medium rounded text-emerald-700 bg-white hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"><i class="fas fa-check mr-1"></i> Selesaikan</button>
                </form>';
            } else {
                $badgeStatus = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">✅ Selesai</span>';
            }

            $actionColumn = '
                <div class="flex justify-center items-center gap-2">
                    '.$badgeStatus.' '.$btnAction.'
                    <button type="button" class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors" data-bs-toggle="modal" data-bs-target="#modalEdit'.$ticket->id.'">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>';

            $data[] = [
                'tanggal' => \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i'),
                'lokasi' => '<span class="fw-bold">'.$ticket->lokasi.'</span>',
                'kategori' => '<span class="badge bg-secondary">'.$ticket->kategori.'</span>',
                'kendala' => \Illuminate\Support\Str::limit($ticket->kendala, 50),
                'action' => $actionColumn
            ];

            // Render HTML Modal Edit (Copas dari file Blade lu)
            $modalsHtml .= view('components.modal-edit', compact('ticket'))->render();
        }

        // 5. KIRIM PAKETAN KOMPLIT KE BLADE
        return response()->json([
            'data' => $data,
            'stats' => [
                'totalBaru' => $totalBaru,
                'totalProses' => $totalProses,
                'totalSelesai' => $totalSelesai,
                'avgRespon' => $avgResponseTime,
                'htmlKategori' => $rekapKategoriHtml,
                'htmlModals' => $modalsHtml
            ]
        ]);
    }
}