<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPaymentsControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_list_payments()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        Payment::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/payments');

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
                        'order_id',
                        'amount',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]
            ],
        ]);
    }
}
