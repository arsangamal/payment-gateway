<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Summary of Payment
 *
 * @property int $id
 * @property int $order_id
 * @property string $payment_gateway
 * @property string $status
 * @property string $external_payment_id
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'status',
        'external_payment_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user_scope', function (Builder $builder) {
            $builder->whereHas('order', function (Builder $query) {
                $query->where('user_id', auth('api')->id());
            });
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function newFactory()
    {
        return PaymentFactory::new();
    }
}
