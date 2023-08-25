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
        $data = ReturPenjualan::with('penjualan','penjualan.member', 'penjualan.suplier', 'penjualan.item')
        ->whereHas('penjualan', fn($query) => $query->where('isRetur', true))
        ->whereYear('created_at', $this->year)
        ->get();
        $total = $data->sum(fn($item) => $item->penjualan->total);
        return view('pages.laporan.retur-penjualan-export', ['data' => $data, 'total' => $total]);   
    }
}
