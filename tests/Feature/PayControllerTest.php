<?php

namespace Tests\Feature;

use App\OrderStatus;
use App\PaymentStatus;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_pay()
    {
        $user = \App\Models\User::factory()->create();
        $order = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
            'payment_gateway' => 'stripe',
        ]);

        $response = $this->actingAs($user)->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'fawry',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', [
            'order_id' => 1,
            'payment_gateway' => 'fawry',
            'status' => PaymentStatus::SUCCESSFUL->value,
        ]);
    }
}
