<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Wajib ada biar map() jalan
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TicketExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Ticket::all();
    }

    // Fungsi ini yang bertugas "Menata Ulang" data sebelum masuk Excel
    public function map($ticket): array
    {
        //set ke bahasa indonesia buat tanggal nya
        Carbon::setLocale('id');
        
        $statusLaporan = 'Tidak Tertangani';
        if($ticket->status == 'Selesai'){
            $statusLaporan = 'Sudah Tertangani';
        }

        return [
            // Kolom 1: Hari/Tanggal
            Carbon::parse($ticket->created_at)->isoFormat('dddd, DD MMMM Y'),    
            // Kolom 2: Jam tiket masuk
            Carbon::parse($ticket->created_at)->format('H.i.s'),
            //Kolom 3: Jam IT merespon tiket
            $ticket->waktu_respon ? Carbon::parse($ticket->waktu_respon)->format('H.i.s') : 'H.i.s',
            //Kolom 4. Kendala yang isinya 'kategori'
            $ticket->kategori,
            //Kolom 5. Lokasi
            $ticket->lokasi,
            //Kolom 6. Status Laporan
            $statusLaporan,
            $ticket->kendala
        ];
    }

    public function headings(): array
    {
        return [
            "Hari/Tanggal", 
            "Jam Pelaporan", 
            "Jam Respon", 
            "Kendala",
            "Lokasi", 
            "Status",
            "Keluhan"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}