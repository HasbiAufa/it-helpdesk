<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // ticket id
            $table->string('lokasi'); // lokasi seperti IGD, Poli, Atau Nurse Station
            $table->enum('kategori', ['Jaringan', 'Komputer', 'Printer', 'Sistem', 'Khanza', 'Antrian', 'Lain-lain']); // tipe yang lagi trouble apa
            $table->text('kendala'); //isi detail keluhan dari chat wa ataupun call wa
            $table->enum('status', ['Baru', 'Proses', 'Selesai'])->default('Baru');
            
            $table->timestamp('waktu_respon')->nullable(); // waktu pas diklik proeses sama IT 
             $table->timestamp('waktu_selesai')->nullable(); //waktu setelah troubleshooting selesai
            
            $table->timestamps(); // waktu pas si pelapor submit tiket (waktu tiket masuk)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
