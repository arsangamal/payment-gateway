<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\OrderStatus;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_order()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        $response = $this->actingAs($user)->putJson("/api/orders/{$order->id}", [
            ...$order->toArray(),
            'items' => [
                [
                    'product_name' => 'Updated Product',
                    'price' => 150,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Order updated successfully',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_name' => 'Updated Product',
            'price' => 150,
            'quantity' => 2,
        ]);
    }
}
