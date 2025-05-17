<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'produk_id', 'nama_produk', 'harga_produk', 'qty', 'total_harga'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
