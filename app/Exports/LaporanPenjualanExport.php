<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualanExport implements FromView
{
    protected $year;
    public function __construct($year)
    {
        $this->year = $year;
    }
    public function view(): View
    {
        $data = Penjualan::with('member', 'suplier', 'item')
            ->join('penjualan_details', 'penjualans.kode_transaksi', '=', 'penjualan_details.kode_transaksi')
            ->where('isRetur', false)
            ->whereYear('penjualans.created_at', $this->year)
            ->whereYear('penjualan_details.created_at', $this->year)->get();
        return view('pages.laporan.penjualan-export', ['data' => $data]);
    }
}
