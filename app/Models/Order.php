<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'meja',
        'catatan',
        'total_harga',
        'status',
        'external_id',
        'checkout_link',
        'status_xendit'
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function details()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
