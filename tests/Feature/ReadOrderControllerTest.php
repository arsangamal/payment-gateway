<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadOrderControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_read_order()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson("/api/orders/$order->id");


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'total',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.total_amount', $order->total_amount)
            ->assertJsonPath('data.status', $order->status);
    }
}
