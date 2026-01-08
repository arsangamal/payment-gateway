<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface IOrderRepository
{
    public function all(): LengthAwarePaginator;
    public function find(int $id): ?Order;
    public function create(array $data): ?Order;
    public function update(int $id, array $data): ?Order;
    public function delete(int $id): bool;

}
