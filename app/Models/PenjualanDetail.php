<?php

namespace App\Models;

use App\Models\User;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'kode_transaksi', 'kode_transaksi');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
