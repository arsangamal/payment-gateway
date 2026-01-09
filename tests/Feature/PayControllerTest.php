<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
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
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
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

    public function test_pay_invalid_gateway()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->actingAs($user)->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'invalid_gateway',
        ]);

        $response->assertStatus(422);
    }


    public function test_pay_unauthenticated()
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::CONFIRMED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'stripe',
        ]);

        $response->assertStatus(401);
    }

    public function test_order_cannot_be_paid_if_not_confirmed()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CANCELLED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->actingAs($user)->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'stripe',
        ]);

        $response->assertStatus(400);
    }


    public function test_can_not_be_paid_twice()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::SUCCESSFUL->value,
        ]);

        $response = $this->actingAs($user)->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'stripe',
        ]);

        $response->assertStatus(400);
    }

    public function test_pay_should_not_take_more_than_3_seconds()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED->value
        ]);
        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
        ]);

        $start = microtime(true);
        $response = $this->actingAs($user)->postJson("/api/payments/$order->id/pay", [
            'gateway' => 'stripe',
        ]);
        $end = microtime(true);
        $duration = $end - $start;

        $response->assertStatus(200);
        $this->assertLessThan(3, $duration, "Payment processing took longer than 3 seconds.");
    }
}
