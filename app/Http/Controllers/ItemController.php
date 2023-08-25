<?php

namespace App\Http\Controllers;

use App\Imports\ItemImport;
use App\Models\Item;
use App\Models\Modal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Item::with('suplier:id,nama');
            return DataTables::of($data)
                ->addColumn('nama_suplier', function ($item) {
                    return $item->suplier->nama;
                })
                ->toJson();
        }
        return view('pages.item.index');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|min:5',
            'kode_barang' => 'required|string|min:5|unique:items,kode',
            'ukuran' => 'required|string',
            'deskripsi' => 'required|string|min:10',
            'harga_beli' => 'required|numeric|min:1',
            'diskon_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:1',
            'diskon_jual' => 'required|numeric|min:0',
            'suplier' => 'required|exists:supliers,id',
            'stok' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }

        try {
            $item = Item::create([
                'suplier_id' => $request->suplier,
                'nama' => $request->nama_barang,
                'kode' => $request->kode_barang,
                'ukuran' => $request->ukuran,
                'stok' => $request->stok,
                'deskripsi' => $request->deskripsi,
                'harga_beli' => $request->harga_beli,
                'diskon_beli' => $request->diskon_beli,
                'harga_jual' => $request->harga_jual,
                'diskon_jual' => $request->diskon_jual,
            ]);
            if ($item) {
                $modal_sekarang = $item->harga_beli * $item->stok;
                $total_modal = Modal::sum('total_modal');
                Modal::create([
                    'modal_sekarang' => $modal_sekarang,
                    'total_modal' => $total_modal + $modal_sekarang
                ]);
            }

            return response()->json([
                'status' => true,
                'msg' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 422);
        }
    }
    public function update(Request $request)
    {
        try {
            // dd($request->all());
            $ceknama = array_key_exists('nama', $request->params);
            $cekharga_beli = array_key_exists('harga_beli', $request->params);
            $cekdiskon_beli = array_key_exists('diskon_beli', $request->params);
            $cekharga_jual = array_key_exists('harga_jual', $request->params);
            $cekdiskon_jual = array_key_exists('diskon_jual', $request->params);
            $ceksuplier = array_key_exists('suplier', $request->params);
            $cekstok = array_key_exists('stok', $request->params);
            if ($ceknama) {
                $data = ['nama' => $request->params['nama']];
                $this->updateData($request, $data);
            }
            if ($cekharga_beli) {
                $data = ['harga_beli' => $request->params['harga_beli']];
                $this->updateData($request, $data);
            }
            if ($cekdiskon_beli) {
                $data = ['diskon_beli' => $request->params['diskon_beli']];
                $this->updateData($request, $data);
            }
            if ($cekharga_jual) {
                $data = ['harga_jual' => $request->params['harga_jual']];
                $this->updateData($request, $data);
            }
            if ($cekdiskon_jual) {
                $data = ['diskon_jual' => $request->params['diskon_jual']];
                $this->updateData($request, $data);
            }
            if ($ceksuplier) {
                $data = ['suplier_id' => $request->params['suplier']];
                $this->updateData($request, $data);
            }
            if ($cekstok) {
                $data = ['stok' => $request->params['stok']];
                $this->updateData($request, $data);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 422);
        }
    }
    public function updateData($request, $data)
    {
        $item = Item::where('kode', $request->params['id'])->first();
        $item->update($data);

        return response()->json([
            'status' => true
        ], 200);
    }
    public function destroy(Request $request)
    {
        $item = Item::findOrFail($request->id);
        try {
            $item->delete();
            return response()->json([
                'status' => true,
                'msg' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }

    public function download(){
        $mime = Storage::mimeType('Format import item.xlsx');
        return response()->download(storage_path('app/public/').'Format import item.xlsx', 'Format import item.xlsx', ['Content-Type' => $mime]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new ItemImport, $file);
        }
        return redirect()->back();
    }
}
