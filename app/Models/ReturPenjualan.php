<?php

namespace App\Models;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturPenjualan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function penjualan_detail()
    {
        return $this->belongsTo(PenjualanDetail::class);
    }
}
