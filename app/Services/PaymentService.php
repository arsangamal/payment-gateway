<?php

namespace App\Services;

use App\Interfaces\IPaymentRepository;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected IPaymentRepository $paymentRepository
    )
    {
    }

    public function all()
    {
        return $this->paymentRepository->all();
    }

    public function find($id)
    {
        return $this->paymentRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->paymentRepository->create($data);
    }
}
