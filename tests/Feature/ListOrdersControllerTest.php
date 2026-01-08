<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListOrdersControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_list_orders()
    {
        $user = User::factory()->create();

        Order::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'status',
                        'total',
                        'items' => [
                            '*' => [
                                'product_name',
                                'price',
                                'quantity',
                            ],
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ]
            ],
        ]);
    }
}
