<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Order
 * @property int $id
 * @property int $user_id
 * @property float $total_price
 * @property string $status
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
