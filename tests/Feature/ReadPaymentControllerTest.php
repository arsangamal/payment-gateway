<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadPaymentControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_read_payment()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
        ]);

        $response = $this->actingAs($user)->getJson("/api/payments/$payment->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'order_id',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonPath('data.id', $payment->id)
            ->assertJsonPath('data.amount', $payment->amount)
            ->assertJsonPath('data.status', $payment->status);
    }
}
