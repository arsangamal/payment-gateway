<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\OrderStatus;
use App\PaymentStatus;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_order()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status'=>OrderStatus::PENDING->value
        ]);

        $order->payment()->create([
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Order deleted successfully',
        ]);

    }

    public function test_cant_delete_order_without_authentication()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(401);
    }

    public function test_cant_delete_non_existent_order()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/orders/999");

        $response->assertStatus(404);
    }


    public function test_cant_delete_paid_order()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status'=>OrderStatus::CONFIRMED->value
        ]);

        $order->payment()->create([
            'status' => PaymentStatus::SUCCESSFUL->value,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Cannot delete an order with successful payment.',
        ]);
    }
}
