<?php

namespace App\Services;

use App\Interfaces\IOrderRepository;
use App\Models\Order;
use App\OrderStatus;
use App\PaymentStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(
        protected IOrderRepository $IOrderRepository
    )
    {}


    public function all(): LengthAwarePaginator
    {
        return $this->IOrderRepository->all();
    }

    public function find(int $id): Order|null
    {
        return $this->IOrderRepository->find($id);
    }

    public function create(array $data): Order|null
    {
        $total = 0;
        foreach ($data['items'] as &$item) {
            $item['subtotal'] = (int)$item['quantity'] * (float)$item['price'];
            $total += $item['subtotal'];
        }

        $data['total'] = $total;
        $data['user_id'] = auth('api')->id();
        $data['status'] = OrderStatus::default()->value;

        $order = $this->IOrderRepository->create($data);

        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
            'user_id' => auth('api')->id(),
        ]);

        return $order;
    }

    public function update(int $id, array $data): Order|null
    {
        $total = 0;
        foreach ($data['items'] as &$item) {
            $item['subtotal'] = (int)$item['quantity'] * (float)$item['price'];
            $total += $item['subtotal'];
        }

        $data['total'] = $total;
        $data['user_id'] = auth('api')->id();

        $order = $this->IOrderRepository->update($id, $data);

        return $order;
    }

    public function delete(int $id): bool
    {
        return $this->IOrderRepository->delete($id);
    }

    public function confirmOrder(int $id): Order|null
    {
        $order = $this->IOrderRepository->find($id);

        if ($order) {
            $order->status = OrderStatus::CONFIRMED->value;
            $order->save();
        }

        return $order;
    }
}
