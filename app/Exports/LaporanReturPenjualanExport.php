<?php

namespace App\Exports;

use App\Models\ReturPenjualan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanReturPenjualanExport implements FromView
{
    protected $year;
    public function __construct($year) {
        $this->year = $year;
    }
    public function view(): View
    {
        $data = ReturPenjualan::join('penjualan_details', 'retur_penjualans.penjualan_detail_id', '=', 'penjualan_details.id')
        ->join('penjualans', 'penjualan_details.kode_transaksi', '=', 'penjualans.kode_transaksi')
        ->join('items', 'penjualans.item_id', '=', 'items.id')
        ->join('supliers', 'penjualans.suplier_id', '=', 'supliers.id')
        ->join('members', 'penjualans.member_id', '=', 'members.id')
        ->whereYear('retur_penjualans.created_at', $this->year)
        ->select('items.nama as barang', 'items.kode', 'members.nama as member', 'supliers.nama as suplier', 'penjualans.qty', 'harga', 'total')
        ->get();
        return view('pages.laporan.retur-penjualan-export', ['data' => $data]);   
    }
}
