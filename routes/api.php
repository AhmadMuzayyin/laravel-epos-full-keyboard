<?php

use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('print', function () {
    $data = PenjualanDetail::where('isPrint', false)->get();
    
    if (!$data->isEmpty()) {
        $data_with_penjualan = $data->load('penjualan');

        $item = [];
        foreach ($data_with_penjualan as $value) {
            array_push($item, [
                'item' => $value->penjualan->item->nama,
                'harga' => $value->penjualan->harga,
                'qty' => $value->penjualan->qty,
                'diskon' => $value->diskon,
                'total' => $value->penjualan->total
            ]);
        }
        $data_print = [];
        array_push($data_print, [
            'faktur' => $data_with_penjualan[0]->nomor_faktur,
            'tgl_faktur' => $data_with_penjualan[0]->tgl_faktur,
            'item' => $item,
            'bayar' => $data_with_penjualan[0]->bayar,
            'kembalian' => $data_with_penjualan[0]->kembalian,
        ]);
        return response()->json($data_print, 200);
    }
});
