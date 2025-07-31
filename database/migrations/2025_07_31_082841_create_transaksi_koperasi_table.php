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
        Schema::create('transaksi_koperasi', function (Blueprint $table) {
            $table->id();
            $table->string('npk', 5);
            $table->string('kode', 4);
            $table->date('tanggal_transaksi');
            $table->integer('qty');
            $table->decimal('harga', 10, 2);
            $table->boolean('bayar')->default(false);
            $table->timestamps();

            $table->foreign('npk')->references('npk')->on('master_karyawan')->onDelete('cascade');
            $table->foreign('kode')->references('kode')->on('master_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_koperasi');
    }
};
