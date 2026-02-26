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
        $ListKategori = ['Jaringan', 'Komputer', 'Printer', 'Khanza', 'Antrian', 'Lain-lain'];
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
        ]);// balik lagi ke halaman awal
        return redirect()->route('home')->with('success', 'Laporan berhasil dicatat!');
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
    public function destroy($id){
        $ticket = Ticket::findOrFail($id); // nyari data
        $ticket->delete(); // hapus

        return redirect()->back()->with('success', 'Tiket Berhasil Dihapus!');
    }
}