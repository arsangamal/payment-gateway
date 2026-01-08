<?php

namespace App\Models;

use App\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Order
 * @property int $id
 * @property int $user_id
 * @property float $total_price
 * @property string $status
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user_scope', function (Builder $builder) {
            $builder->where('user_id', auth('api')->id());
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function newFactory()
    {
        return OrderFactory::new();
    }
}
