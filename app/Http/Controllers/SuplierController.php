<?php

namespace App\Http\Controllers;

use App\Models\Suplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SuplierController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Suplier::all();
            return DataTables::of($data)->toJson();
        }

        return view('pages.suplier.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_suplier' => 'required|min:5|string',
            'alamat' => 'required|min:5|string',
            'telepon' => 'required|min:5|string',
            'kontak' => 'required|min:5|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }

        try {
            $kode = 'SPR-ALB-' . date('Y-m-d H:s');
            $Suplier = Suplier::create([
                'kode' => $kode,
                'nama' => $request->nama_suplier,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'kontak' => $request->kontak,
            ]);

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
            $cekalamat = array_key_exists('alamat', $request->params);
            $cekkontak = array_key_exists('kontak', $request->params);
            $cektelepon = array_key_exists('telepon', $request->params);
            $cekdiskon = array_key_exists('diskon', $request->params);
            if ($ceknama) {
                $data = ['nama' => $request->params['nama']];
                $this->updateData($request, $data);
            }
            if ($cekalamat) {
                $data = ['alamat' => $request->params['alamat']];
                $this->updateData($request, $data);
            }
            if ($cekkontak) {
                $data = ['kontak' => $request->params['kontak']];
                $this->updateData($request, $data);
            }
            if ($cektelepon) {
                $data = ['telepon' => $request->params['telepon']];
                $this->updateData($request, $data);
            }
            if ($cekdiskon) {
                $data = ['diskon' => $request->params['diskon']];
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
        $Suplier = Suplier::where('kode', $request->params['id'])->first();
        $Suplier->update($data);

        return response()->json([
            'status' => true
        ], 200);
    }


    public function destroy(Request $request)
    {
        $Suplier = Suplier::findOrFail($request->id);
        try {
            $Suplier->load('item');
            if ($Suplier->item->isEmpty()) {
            $Suplier->delete();
            return response()->json([
                'status' => true,
                'msg' => 'success'
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'msg' => 'data suplier telah digunakan pada data stok barang'
            ], 200);
        }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ], 200);
        }
    }
}
