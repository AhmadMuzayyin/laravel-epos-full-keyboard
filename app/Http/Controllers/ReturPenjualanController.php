<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\ReturPenjualan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $penjualan = ReturPenjualan::join('penjualan_details', 'retur_penjualans.penjualan_detail_id', '=', 'penjualan_details.id')
        ->join('penjualans', 'penjualan_details.kode_transaksi', '=', 'penjualans.kode_transaksi')
        ->join('users', 'penjualan_details.user_id', '=', 'users.id')
        ->join('items', 'penjualans.item_id', '=', 'items.id')->where('retur_penjualans.status', false)->get();
        // dd($penjualan);
        $dataPenjualan = [];
        $pengembalian = 0;
        if (!$penjualan->isEmpty()) {
            foreach ($penjualan as $key => $value) {
                array_push($dataPenjualan, [
                    'id' => $value->id,
                    'kode' => $value->kode,
                    'item' => $value->nama,
                    'harga' => $value->harga,
                    'qty' => $value->qty,
                    'total' => $value->total,
                    'nomor_faktur' => $value->nomor_faktur,
                    'nomor_faktur' => $value->nomor_faktur,
                    'kasir' => $value->name
                ]);
            }
            $pengembalian = $penjualan[0]->total_tagihan - ($penjualan[0]->total_tagihan * $penjualan[0]->diskon / 100);
        }
        // dd($pengembalian);
        if (request()->ajax()) {
            return DataTables::of($dataPenjualan)
                ->setRowId('id')
                ->toJson();
        }

        return view('pages.retur_penjualan.index', [
            'penjualan' => $dataPenjualan,
            'pengembalian' => $pengembalian,
            'banyak' => $penjualan->count()
        ]);
    }
    public function store(Request $request)
    {
        try {
            $data = PenjualanDetail::where('nomor_faktur', $request['kode'])->first();
            if ($data) {
                $validasi = ReturPenjualan::where('penjualan_detail_id', $data->id)->first();
                if (!$validasi) {
                    $retur = [];
                    foreach ($data->penjualan as $key => $value) {
                        $retur = ReturPenjualan::create([
                            'penjualan_detail_id' => $data->id,
                            'penjualan_id' => $value->id
                        ]);
                    }
                    foreach ($retur->where('status', false)->get()->load('penjualan') as $key => $value) {
                        $item = Item::where('id', $value->penjualan->item_id)->first();
                        $item->stok = $item->stok + $value->penjualan->qty;
                        $item->save();
                    }
                    return response()->json([
                        'status' => true,
                        'data' => ReturPenjualan::with('penjualan_detail', 'penjualan_detail.user')->firstWhere('status', false)
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Data sudah diretur'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'Tidak ada penjualan dari faktur ini'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function update(Request $request)
    {
        try {
            $data = ReturPenjualan::where('status', false)->first();
            PenjualanDetail::where('id', $data->penjualan_detail_id)->update(['isRetur' => true]);
            $data->update([
                'status' => true
            ]);
            return response()->json([
                'status' => true,
                'msg' => 'berhasil'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function destroy(Request $request)
    {
        $penjualan = ReturPenjualan::where('id',$request->id);
        try {
            // dd($penjualan->get()->load('penjualan'));
            foreach ($penjualan->get()->load('penjualan') as $key => $value) {
                $item = Item::where('id', $value->penjualan->item_id)->first();
                $item->stok = $item->stok - $value->penjualan->qty;
                $item->save();
            }
            $penjualan->delete();
            return response()->json([
                'status' => true,
                'msg' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function destroyAll(Request $request)
    {
        $penjualan =  ReturPenjualan::where('status', false);
        try {
            foreach ($penjualan->get()->load('penjualan') as $key => $value) {
                $item = Item::where('id', $value->penjualan->item_id)->first();
                $item->stok = $item->stok - $value->penjualan->qty;
                $item->save();
            }
            $penjualan->delete();
            return response()->json([
                'status' => true,
                'msg' => 'Data retur penjualan berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
}
