<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    public function test_indexPage()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/invoice');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/invoice/in_1LXnaQSGKiNo0lKYWPCnNjGc');
        $response->assertStatus(200);

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $this->assertGuest();

        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/invoice');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/invoice/in_1LXnaQSGKiNo0lKYWPCnNjGc');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/payment');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/payment/in_1LXnaQSGKiNo0lKYWPCnNjGc');
        $response->assertStatus(200);
    }
}
