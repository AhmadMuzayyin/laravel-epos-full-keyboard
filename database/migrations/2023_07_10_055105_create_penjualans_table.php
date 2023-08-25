<?php

use App\Models\Item;
use App\Models\Member;
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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)->constrained();
            $table->foreignIdFor(Member::class)->constrained();
            $table->foreignIdFor(Suplier::class)->constrained();
            $table->string('kode_transaksi');
            $table->bigInteger('harga');
            $table->bigInteger('qty');
            $table->bigInteger('total');
            $table->boolean('status')->default(false);
            $table->boolean('isRetur')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
