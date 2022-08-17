<?php
namespace Tests\Feature;

use App\Models\Subscription;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    public function test_indexPage()
    {
        $subscription = Subscription::first();
        $id = $subscription->stripe_id;

        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/userSubscription');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/userSubscription/'.$id);
        $response->assertStatus(200);

    }
}
