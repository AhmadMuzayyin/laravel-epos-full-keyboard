<?php

use App\Models\Item;
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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)->constrained();
            $table->string('nomor_faktur')->nullable();
            $table->bigInteger('qty')->default(0);
            $table->bigInteger('harga_beli')->default(0);
            $table->bigInteger('diskon_beli')->default(0);
            $table->bigInteger('harga_jual')->default(0);
            $table->bigInteger('diskon_jual')->default(0);
            $table->bigInteger('total')->default(0);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
