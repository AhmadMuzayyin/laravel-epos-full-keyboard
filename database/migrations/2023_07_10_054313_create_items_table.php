<?php

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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Suplier::class)->constrained();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->bigInteger('stok')->default(0);
            $table->string('ukuran');
            $table->text('deskripsi');
            $table->bigInteger('harga_beli')->default(0);
            $table->bigInteger('diskon_beli')->default(0);
            $table->bigInteger('harga_jual')->default(0);
            $table->bigInteger('diskon_jual')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
