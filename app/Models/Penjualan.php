<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Member;
use App\Models\Suplier;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function suplier(){
        return $this->belongsTo(Suplier::class);
    }
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function penjualan_detail(){
        return $this->belongsTo(PenjualanDetail::class, 'kode_transaksi', 'kode_transaksi')->where('isRetur', false);
    }
}
