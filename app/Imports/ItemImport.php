<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd(count($row));
        $suplier = \App\Models\Suplier::firstWhere('kode', $row['suplier']);
        return new Item([
            'suplier_id'  => $suplier->id,
            'nama' => $row['nama'],
            'kode'    => $row['kode'],
            'stok'    => $row['stok'],
            'ukuran'    => $row['ukuran'],
            'deskripsi'    => $row['deskripsi'],
            'harga_beli'    => $row['harga_beli'],
            'diskon_jbeli'    => $row['diskon_beli'],
            'harga_jual'    => $row['harga_jual'],
            'diskon_jual'    => $row['diskon_jual'],
        ]);
    }
}
