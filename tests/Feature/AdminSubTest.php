<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Tests\TestCase;

class AdminSubTest extends TestCase
{
    public function test_indexPage()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $subscriptionsID = $this->stripe->subscriptions->all(['limit' => 1]);
        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/subscription');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8002/subscription/sub_1LVDEFSGKiNo0lKYHSYfOyRu');
        $response->assertStatus(200);

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $this->assertGuest();

        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/subscription');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8002/subscription/sub_1LVDEFSGKiNo0lKYHSYfOyRu');
        $response->assertStatus(200);
    }
}
