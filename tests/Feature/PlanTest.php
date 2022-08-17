<?php

namespace Tests\Feature;

use App\Models\Plan;
use Tests\TestCase;

class PlanTest extends TestCase
{

    public function test_indexPage()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);
        $plan = Plan::first();
        $id = $plan->id;

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/plans');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/plans/'.$id);
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/plans/create');
        $response->assertStatus(200);
        $response = $this->call('POST', 'http://127.0.0.1:8000/plans', [
            'name' => 'basic new 11',
            'cost' => '250',
            'description' => 'basic plan'
        ]);
        $plan = Plan::where('name','basic new 11')->delete();
        $response->assertStatus(302);

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $this->assertGuest();

        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/plans');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/plans/'.$id);
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/plans/create');
        $response->assertStatus(302);
        $response = $this->call('POST', 'http://127.0.0.1:8000/plans', [
            'name' => 'basic new 11',
            'cost' => '250',
            'description' => 'basic plan'
        ]);
        $plan = Plan::where('name','basic new 11')->delete();
        $response->assertStatus(302);
    }
    public function test_updatePlan()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);
        $plan = Plan::first();
        $id = $plan->id;

        $this->assertAuthenticated();
        $response = $this->call('PUT', 'http://127.0.0.1:8000/plans/'.$id, [
            'name' => 'basic new',
            'description' => 'basic plan 1'
        ]);
        $response->assertStatus(302);
    }
    public function test_editPlan()
    {
        $plan = Plan::first();
        $id = $plan->id;
        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->call('GET', 'http://127.0.0.1:8000/plans/'.$id.'/edit', [
            'name' => 'basic new',
            'description' => 'basic plan 1'
        ]);
        $response->assertStatus(200);
    }
}
