<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->unique()->index(); // Format: AST-20240101-0001

            // Informasi Utama
            $table->string('name');                        // Nama perangkat, misal: Laptop Dell Latitude
            $table->enum('category', [
                'Laptop', 'Desktop/PC', 'Printer', 'Monitor',
                'Switch/Router', 'UPS', 'Scanner', 'Proyektor', 'Kamera', 'Lainnya'
                // -- TAMBAH KATEGORI BARU DI ATAS BARIS INI --
            ]);
            $table->string('brand')->nullable();           // Merek HW, misal: Dell, HP, Canon
            $table->string('model')->nullable();           // Seri/tipe dari merek, misal: Latitude 5420
            $table->string('serial_number')->nullable()->unique(); // SN dari sticker perangkat

            // Status & Penugasan
            $table->enum('condition', ['Baik', 'Perlu Servis', 'Rusak', 'Tidak Aktif'])->default('Baik');
            $table->string('location')->nullable();        // Unit/ruangan tempat aset berada
            $table->string('assigned_to')->nullable();     // Nama user/divisi pemegang aset

            // Pembelian & Garansi
            $table->date('purchase_date')->nullable();     // Tanggal pembelian
            $table->date('warranty_expiry')->nullable();   // Tanggal akhir garansi
            $table->decimal('purchase_price', 15, 2)->nullable(); // Harga beli dalam Rupiah

            // Spesifikasi Teknis (Opsional, fleksibel bentuk teks bebas)
            $table->text('specs')->nullable();             // Spek lengkap, misal: RAM 16GB, SSD 512GB
            $table->text('notes')->nullable();             // Catatan tambahan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
