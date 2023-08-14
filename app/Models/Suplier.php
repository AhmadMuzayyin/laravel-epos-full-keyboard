<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suplier extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function item(){
        return $this->hasMany(Item::class);
    }
}
