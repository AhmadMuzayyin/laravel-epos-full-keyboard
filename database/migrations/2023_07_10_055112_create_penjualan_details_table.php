<?php

use App\Models\Penjualan;
use App\Models\User;
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
        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('kode_transaksi');
            $table->string('nomor_faktur')->unique();
            $table->bigInteger('qty');
            $table->bigInteger('total_tagihan');
            $table->bigInteger('diskon');
            $table->bigInteger('bayar');
            $table->bigInteger('kembalian');
            $table->boolean('isPrint')->default(false);
            $table->boolean('isRetur')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_details');
    }
};
