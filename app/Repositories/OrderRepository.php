<?php

namespace App\Repositories;

use App\Interfaces\IOrderRepository;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements IOrderRepository
{

    public function all(): LengthAwarePaginator
    {
        $query = Order::query();

        $query->when(request()->has('status'), function ($q) {
            $q->where('status', request()->get('status'));
        });

        return $query->with('items')->paginate();
    }

    public function find(int $id): ?Order
    {
        return Order::find($id)->load('items');
    }

    public function create(array $data): ?Order
    {
        $order = Order::create($data);

        $order->items()->createMany($data['items']);

        return $order->load('items');
    }

    public function update(int $id, array $data): ?Order
    {
        $order = Order::findOrFail($id);

        $order->items()->delete();
        $order->items()->createMany($data['items']);

        return $order->update($data) ? $order->load('items') : null;
    }

    public function delete(int $id): bool
    {
        $order = Order::findOrFail($id);

        return $order->delete();
    }
}
