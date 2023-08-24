<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanReturPenjualanExport;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\ReturPenjualan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public $year;
    public function __construct() {
        $this->year = request()->get('year') ?? date('Y');
    }
    public function penjualan()
    {
        $tabel = Penjualan::with('member', 'suplier', 'item')
            ->join('penjualan_details', 'penjualans.kode_transaksi', '=', 'penjualan_details.kode_transaksi')
            ->where('isRetur', false)
            ->whereYear('penjualans.created_at', $this->year)
            ->get();
        // dd($tabel);
        if (request()->ajax()) {
            return DataTables::of($tabel)->toJson();
        }
        // chart line
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[date('Y-m', strtotime("$this->year-$month-01"))] = 0;
        }
        $lineData = PenjualanDetail::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_tagihan) as total_value')
            ->where('isRetur', false)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_value', 'month')
            ->all();
        $line = [];
        foreach ($months as $month => $defaultValue) {
            $line[] = [
                'month' => $month,
                'total_value' => isset($lineData[$month]) ? $lineData[$month] : $defaultValue,
            ];
        }
        // chart bar
        $barData = Penjualan::join('members', 'penjualans.member_id', '=', 'members.id')
            ->join('penjualan_details', 'penjualans.kode_transaksi', '=', 'penjualan_details.kode_transaksi')
            ->selectRaw('penjualans.member_id, members.nama as nama_member, SUM(penjualans.total) as total_value')
            ->where('isRetur', false)
            ->where('status', true)
            ->whereYear('penjualans.created_at', $this->year)
            ->groupBy('penjualans.member_id', 'members.nama')
            ->get();
        // dd($barData);
        return view('pages.laporan.penjualan', compact('line', 'barData', 'tabel'));
    }
    public function export_penjualan()
    {
        $year = request()->get('year') ?? date('Y');
        return Excel::download(new LaporanPenjualanExport($year), "laporan-penjualan-$year.xlsx");
    }
    public function retur_penjualan()
    {
        $tabel = ReturPenjualan::join('penjualan_details', 'retur_penjualans.penjualan_detail_id', '=', 'penjualan_details.id')
        ->join('penjualans', 'penjualan_details.kode_transaksi', '=', 'penjualans.kode_transaksi')
        ->join('items', 'penjualans.item_id', '=', 'items.id')
        ->join('supliers', 'penjualans.suplier_id', '=', 'supliers.id')
        ->join('members', 'penjualans.member_id', '=', 'members.id')
        ->whereYear('retur_penjualans.created_at', $this->year)
        ->select('items.nama as barang', 'items.kode', 'members.nama as member', 'supliers.nama as suplier', 'penjualans.qty', 'harga', 'total')
        ->get();
        if (request()->ajax()) {
            return DataTables::of($tabel)->toJson();
        }
        return view('pages.laporan.retur-penjualan', compact('tabel'));
    }
    public function export_retur_penjualan()
    {
        return Excel::download(new LaporanReturPenjualanExport($this->year), "laporan-retur penjualan-$this->year.xlsx");
    }
    public function pembelian()
    {
    }
    public function laba_rugi()
    {
    }
}
