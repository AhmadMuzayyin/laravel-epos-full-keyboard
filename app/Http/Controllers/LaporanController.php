<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanReturPenjualanExport;
use App\Models\Item;
use App\Models\Modal;
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
    public $from;
    public $to;
    public function __construct()
    {
        $this->year = request()->get('year') ?? date('Y');
        $this->from = request()->get('from') ?? date('Y-m-d');
        $this->to = (request()->get('to') == date('Y-m-d') ? date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) : request()->get('to')) ?? date('Y-m-d', strtotime(date('Y-m-d') . '+1 day'));
    }
    public function penjualan()
    {
        $tabel = Penjualan::with('member', 'suplier', 'item')
            ->join('penjualan_details', 'penjualans.kode_transaksi', '=', 'penjualan_details.kode_transaksi')
            ->where('penjualans.isRetur', false)
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
        $lineData = PenjualanDetail::selectRaw('DATE_FORMAT(penjualan_details.created_at, "%Y-%m") as month, SUM(total_tagihan) as total_value')
            ->join('penjualans', 'penjualans.kode_transaksi', '=', 'penjualan_details.kode_transaksi')
            ->where('penjualans.isRetur', false)
            ->whereYear('penjualan_details.created_at', $this->year)
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
        $tabel = ReturPenjualan::with('penjualan','penjualan.member', 'penjualan.suplier', 'penjualan.item')
        ->whereHas('penjualan', fn($query) => $query->where('isRetur', true))
        ->whereYear('created_at', $this->year)
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
    public function laba_rugi()
    {
        $modal = Modal::sum('modal_sekarang');
        $penjualan = PenjualanDetail::with('penjualan')->whereBetween('created_at', [$this->from, $this->to])->whereHas('penjualan', fn($query) => $query->where('isRetur', false))->sum('total_tagihan');
        $hasil = $penjualan - $modal;
        return view('pages.laporan.laba_rugi', compact('modal', 'hasil', 'penjualan'));
    }
}
