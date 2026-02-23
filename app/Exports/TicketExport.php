<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TicketExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Ticket::query();

        if($this->filter == 'hari_ini'){
            $query->whereDate('created_at', Carbon::today());
        } elseif($this->filter == 'bulan_ini'){
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif($this->filter == 'tahun_ini'){
            $query->whereYear('created_at', Carbon::now()->year);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function map($ticket): array
    {
        Carbon::setLocale('id');
        
        $statusLaporan = 'Tidak Tertangani';
        if($ticket->status == 'Selesai'){
            $statusLaporan = 'Sudah Tertangani';
        }

        return [
            Carbon::parse($ticket->created_at)->isoFormat('dddd, DD MMMM Y'),    
            Carbon::parse($ticket->created_at)->format('H.i.s'),
            $ticket->waktu_respon ? Carbon::parse($ticket->waktu_respon)->format('H.i.s') : '-',
            $ticket->kategori,
            $ticket->lokasi,
            $statusLaporan,
            $ticket->kendala
        ];
    }

    public function headings(): array
    {
        return ["Hari/Tanggal", "Jam Pelaporan", "Jam Respon", "Kendala", "Lokasi", "Status", "Keluhan"];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]],];
    }
}