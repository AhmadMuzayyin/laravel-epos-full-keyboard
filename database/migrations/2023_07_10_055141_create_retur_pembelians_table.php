<?php

use App\Models\Pembelian;
use App\Models\Suplier;
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
        Schema::create('retur_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Suplier::class)->constrained();
            $table->foreignIdFor(Pembelian::class)->constrained();
            $table->bigInteger('harga');
            $table->bigInteger('qty');
            $table->bigInteger('qty_retur');
            $table->bigInteger('diskon');
            $table->bigInteger('total_retur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};
