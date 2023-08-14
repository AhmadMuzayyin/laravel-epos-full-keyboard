<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function member(){
        return $this->belongsTo(Member::class);
    }
}
