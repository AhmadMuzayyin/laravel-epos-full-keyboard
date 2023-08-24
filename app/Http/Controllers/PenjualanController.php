<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Member;
use App\Models\Penjualan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use App\Models\Setting;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::where('status', false)->get();

        $data = [];


        $dataPenjualan = [];
        if ($penjualan->isEmpty()) {
            $data = [];
            array_push($dataPenjualan, [
                'total_pembayaran' => 0,
                'total_penjualan' => 0,
                'no_faktur' => fake()->randomNumber(5) . ' / ' . date('Y-m-d') . ' / ' . date('H:i:s'),
                'data' => $data
            ]);
        } else {
            $data = $penjualan->load('item', 'member');

            $total_pembayaran = 0;
            $total_penjualan = 0;
            foreach ($data as $value) {
                $total_pembayaran += $value->total;
                $total_penjualan += $value->qty;
            }

            array_push($dataPenjualan, [
                'total_pembayaran' => $total_pembayaran,
                'total_penjualan' => $total_penjualan,
                'no_faktur' => fake()->randomNumber(5) . ' / ' . date('Y-m-d') . ' / ' . date('H:i:s'),
                'data' => $data
            ]);
        }

        if (request()->ajax()) {
            return DataTables::of($dataPenjualan[0]['data'])
                ->setRowId('id')
                ->toJson();
        }

        return view('pages.penjualan.index', [
            'penjualan' => $dataPenjualan,
        ]);
    }
    public function store(Request $request)
    {
        try {
            if ($request['status'] == true) {
                $penjualan = Penjualan::where('status', false)->first();
                PenjualanDetail::create([
                    'user_id' => auth()->user()->id,
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
                    'user_id' => auth()->user()->id,
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
    public function print_nota()
    {
        $data = PenjualanDetail::where('isPrint', false)->first();
        $setting = Setting::first()->toArray();
        $data_print = [];
        $status = collect();
        if ($data) {
            $status = true;
            $data_print = $data->load('penjualan');
        } else {
            $status = false;
        }
        return view('pages.penjualan.print', compact('data_print', 'status', 'setting'));
    }
    public function update(Request $request)
    {
        try {
            $data = Penjualan::firstWhere('id', $request['params']['id']);
            if ($data) {
                $stok_perubahan = $request['params']['qty'];
                if ($data->item->stok >= $stok_perubahan) {
                    $total = $data->harga * $request['params']['qty'];
                    $data->qty = $stok_perubahan;
                    $data->total = $total;
                    $data->save();
                    return response()->json([
                        'status' => true,
                        'msg' => 'Berhasil merubah qty.'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Stok ' . $data->item->nama . ' tinggal ' . $data->item->stok
                    ], 200);
                }
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
    public function saveBarang(Request $request)
    {
        // dd($request->all());
        try {
            $item = Item::firstWhere('kode', $request['kode']);
            $getKode_transaksi = Penjualan::firstWhere('status', false);
            if ($item) {
                // cek stok
                if ($item->stok > 0) {
                    if ($getKode_transaksi) {
                        $item_with_kode = Penjualan::create([
                            'item_id' => $item->id,
                            'suplier_id' => $item->suplier_id,
                            'member_id' => $request['member_id'],
                            'kode_transaksi' => $getKode_transaksi->kode_transaksi,
                            'harga' => $item->harga_jual,
                            'qty' => 1,
                            'total' => $item->harga_jual * 1
                        ]);
                        return response()->json([
                            'status' => true,
                            'data' => $item_with_kode->load('item')
                        ], 200);
                    } else {
                        $item_withot_kode = Penjualan::create([
                            'item_id' => $item->id,
                            'suplier_id' => $item->suplier_id,
                            'member_id' => $request['member_id'],
                            'kode_transaksi' => fake()->numberBetween(10),
                            'harga' => $item->harga_jual,
                            'qty' => 1,
                            'total' => $item->harga_jual * 1
                        ]);
                        return response()->json([
                            'status' => true,
                            'data' => $item_withot_kode->load('item')
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Stok ' . $item->nama . ' Habis'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'Barang yang dicari tidak ada'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
    public function update_print(Request $request)
    {
        try {
            $penjualan = Penjualan::where('status', false)->first();
            Penjualan::where('status', false)->update([
                'status' => true
            ]);
            if ($penjualan) {
                PenjualanDetail::where('kode_transaksi', $penjualan->kode_transaksi)->where('isPrint', false)->update([
                    'isPrint' => true
                ]);
            }

            return response()->json([
                'status' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
}
