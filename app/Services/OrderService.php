<?php

namespace App\Services;

use App\Interfaces\IOrderRepository;
use App\Models\Order;
use App\OrderStatus;
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

    public function createOrder(array $data): Order|null
    {
        $total = 0;
        foreach ($data['items'] as &$item) {
            $item['subtotal'] = (int)$item['quantity'] * (float)$item['price'];
            $total += $item['subtotal'];
        }

        $data['total'] = $total;
        $data['user_id'] = auth('api')->id();
        $data['status'] = OrderStatus::default()->value;

        return $this->IOrderRepository->create($data);
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

        return $this->IOrderRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->IOrderRepository->delete($id);
    }
}
