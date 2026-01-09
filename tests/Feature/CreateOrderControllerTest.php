<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CreateOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order()
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->postJson('/api/orders', [
            'items' => [
                [
                    'product_name' => 'macbook pro',
                    'price' => 2500,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'user_id',
                'items' => [
                    [
                        'product_name',
                        'price',
                        'quantity',
                    ],
                ],
                'created_at',
                'updated_at',
            ]
        ]);
    }


    public function test_cant_create_order_without_authentication()
    {
        $response = $this->postJson('/api/orders', [
            'items' => [
                [
                    'product_name' => 'macbook pro',
                    'price' => 2500,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(401);

    }

    public function test_create_order_without_items()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->postJson('/api/orders', [
            'items' => [],
        ]);

        $response->assertStatus(422);
    }

}
