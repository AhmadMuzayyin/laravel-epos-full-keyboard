<?php

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
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
        Schema::create('retur_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PenjualanDetail::class)->constrained();
            $table->foreignIdFor(Penjualan::class)->constrained();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_penjualans');
    }
};
