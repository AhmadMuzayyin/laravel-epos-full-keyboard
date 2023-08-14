<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::with('item')->where('status', false)->get();
        $total_pembelian = 0;
        $diskon = 0;
        $jumlah = 0;
        foreach ($pembelian as $key => $value) {
            $total_pembelian += $value->harga_beli;
            $diskon += $value->diskon_beli;
            $jumlah += $value->qty;
        }
        $total_dengan_diskon = $total_pembelian - ($total_pembelian * $diskon / 100);
        $total_semua = $total_dengan_diskon * $jumlah;
        $faktur_kasir = [];
        if ($pembelian->empty()) {
            array_push($faktur_kasir, [
                'faktur' => $pembelian[0]->nomor_faktur
            ]);
        }else{
            array_push($faktur_kasir, [
                'faktur' => ''
            ]);
        }
        if (request()->ajax()) {
            return DataTables::of($pembelian)
                ->setRowId('id')
                ->toJson();
        }

        return view('pages.pembelian.index', [
            'pembelian' => $pembelian,
            'total_pembelian' => $total_semua,
            'faktur_kasir' => $faktur_kasir,
            'jumlah' => $jumlah
        ]);
    }
    public function store(Request $request)
    {
        try {
            if ($request['status'] === true) {
                $penjualan = Penjualan::where('status', false)->first();
                PenjualanDetail::create([
                    'kode_transaksi' => $penjualan->kode_transaksi,
                    'nomor_faktur' => $request['faktur'],
                    'qty' => $request['qty'],
                    'total_tagihan' => $request['total_pembayaran'],
                    'diskon' => $request['diskon'],
                    'bayar' => $request['bayar'],
                    'kembalian' => $request['kembali'],
                ]);

                $data_penjualan = Penjualan::where('status', false)->get();
                foreach ($data_penjualan as $value) {
                    $item = Item::where('id', $value->item_id)->first();
                    $item->stok = $item->stok - $value->qty;
                    $item->save();
                }

                return response()->json([
                    'status' => true
                ], 200);
            } else {
                $penjualan = Penjualan::where('status', false)->first();
                PenjualanDetail::create([
                    'kode_transaksi' => $penjualan->kode_transaksi,
                    'nomor_faktur' => $request['faktur'],
                    'qty' => $request['qty'],
                    'total_tagihan' => $request['total_pembayaran'],
                    'diskon' => $request['diskon'],
                    'bayar' => $request['bayar'],
                    'kembalian' => $request['kembali'],
                    'isPrint' => true
                ]);

                $data_penjualan = Penjualan::where('status', false)->get();
                foreach ($data_penjualan as $value) {
                    $item = Item::where('id', $value->item_id)->first();
                    $item->stok = $item->stok - $value->qty;
                    $item->save();
                }

                Penjualan::where('status', false)->update([
                    'status' => true
                ]);

                return response()->json([
                    'status' => true
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
        // dd($request->all());
        try {
            $data = Pembelian::firstWhere('id', $request['params']['id']);
            if ($data) {
                $data->update([
                    $request['params']['field'] => $request['params']['value']
                ]);
                return response()->json([
                    'status' => true,
                    'msg' => 'Berhasil merubah data'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'data tidak ditemukan'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function save(Request $request)
    {
        try {
            dd($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function destroy(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id);
        try {
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
        try {
            Penjualan::where('status', false)->delete();
            return response()->json([
                'status' => true,
                'msg' => 'Data penjualan berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
}
