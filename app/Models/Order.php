<?php

namespace App\Models;

use App\Events\OrderCreated;
use App\Events\OrderUpdated;
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

    // Broadcasting events when an order is created or updated
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($order) {
    //         event(new OrderCreated($order));
    //     });

    //     static::updated(function ($order) {
    //         event(new OrderUpdated($order));
    //     });
    // }
}
