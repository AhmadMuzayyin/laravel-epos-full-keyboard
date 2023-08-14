<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Member::all();
            return DataTables::of($data)->toJson();
        }

        return view('pages.member.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required|min:5|string',
            'alamat' => 'required|min:5|string',
            'telepon' => 'required|min:5|string',
            'kontak' => 'required|min:5|string',
            'diskon' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }

        try {
            $kode = 'KPR-ALB-' . date('Y-m-d H:s');
            $Member = Member::create([
                'kode' => $kode,
                'nama' => $request->nama_member,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'kontak' => $request->kontak,
                'diskon' => $request->diskon
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
        $Member = Member::where('kode', $request->params['id'])->first();
        $Member->update($data);

        return response()->json([
            'status' => true
        ], 200);
    }


    public function destroy(Request $request)
    {
        $Member = Member::findOrFail($request->id);
        try {
            $Member->delete();
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
}
