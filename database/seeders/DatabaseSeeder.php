<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create(

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);
        \App\Models\Member::create([
            'nama' => 'Umum',
            'kode' => \Illuminate\Support\Str::random(10),
            'kontak' => 'Umum',
            'telepon' => '123456789',
            'alamat' => 'Umum',
            'diskon' => 0,
        ]);

        $suplier = \App\Models\Suplier::create([
            'nama' => 'PT. LPG',
            'kode' => \Illuminate\Support\Str::random(10),
            'kontak' => 'Ahmad Muzayyin',
            'telepon' => 123456789,
            'alamat' => 'Sumenep Kota'
        ]);

        \App\Models\Item::create([
            'suplier_id' => $suplier->id,
            'nama' => 'Gas LPG',
            'kode' => 'TBL-123456789',
            'stok' => 1000,
            'ukuran' => '3Kg',
            'deskripsi' => 'Tabung Kecil',
            'harga_beli' => 17000,
            'diskon_beli' => 0,
            'harga_jual' => 20000,
            'diskon_jual' => 0
        ]);

        \App\Models\Setting::create([
            'nama_toko' => 'Kopontren Al-Ibrohimiy',
            'alamat_toko' => 'Masaran Pragaan Sumenep',
            'kontak' => '085155353793',
            'jenis_kertas' => 'kecil'
        ]);
    }
}
