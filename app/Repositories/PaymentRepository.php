<?php

namespace App\Repositories;

use App\Interfaces\IPaymentRepository;
use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRepository implements IPaymentRepository
{

    public function all(): LengthAwarePaginator
    {
        $payments = Payment::with('order')->paginate();

        return $payments;
    }

    public function find(int $id): ?Payment
    {
        return Payment::with('order')->find($id);
    }

    public function create(array $data): Payment
    {
        $payment = Payment::create($data);

        return $payment;
    }
}
