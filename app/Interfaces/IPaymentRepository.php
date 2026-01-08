<?php

namespace App\Interfaces;

use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;

interface IPaymentRepository
{
    public function all(): LengthAwarePaginator;

    public function find(int $id): ?Payment;

    public function create(array $data): Payment;

}
