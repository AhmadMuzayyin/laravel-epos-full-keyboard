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
        $penjualan = ReturPenjualan::with('penjualan', 'penjualan_detail')->where('status', false)->get();
        $dataPenjualan = [];
        $pengembalian = 0;
        if (!$penjualan->isEmpty()) {
            foreach ($penjualan as $key => $value) {
                array_push($dataPenjualan, [
                    'id' => $value->id,
                    'kode' => $value->penjualan->item->kode,
                    'item' => $value->penjualan->item->nama,
                    'harga' => $value->penjualan->harga,
                    'qty' => $value->penjualan->qty,
                    'total' => $value->penjualan->total,
                    'nomor_faktur' => $value->penjualan_detail->nomor_faktur,
                    'nomor_faktur' => $value->penjualan_detail->nomor_faktur,
                    'kasir' => $value->penjualan_detail->user->name
                ]);
            }
            $pengembalian = $penjualan[0]->penjualan_detail->total_tagihan - ($penjualan[0]->penjualan_detail->total_tagihan * $penjualan[0]->penjualan_detail->diskon / 100);
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
            ReturPenjualan::where('status', false)->update([
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
