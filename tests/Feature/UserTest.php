<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_indexPage()
    {
        $subscription = User::first();
        $id = $subscription->id;

        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/user');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/user/'.$id);
        $response->assertStatus(200);

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $this->assertGuest();

        $response = $this->post('/login', [
            'email' => 'aditi@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('http://127.0.0.1:8000/user');
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/user/'.$id);
        $response->assertStatus(200);
        $response = $this->get('http://127.0.0.1:8000/user/'.$id.'/edit');
        $response->assertStatus(200);
        $response = $this->put('http://127.0.0.1:8000/user/'.$id);
        $response->assertStatus(200);
        $response = $this->delete('http://127.0.0.1:8000/user/'.$id);
        $response->assertStatus(200);
    }

    public function test_updateUser()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $user = User::factory()->create();

        $this->assertAuthenticated();
        $response = $this->call('PUT', 'http://127.0.0.1:8000/user/'.$user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
        ]);
        $response->assertStatus(302);
        $user = User::findOrFail($user->id)->delete();

    }
    public function test_editUser()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $user = User::factory()->create();
        $this->assertAuthenticated();
        $response = $this->call('GET', 'http://127.0.0.1:8000/user/'.$user->id.'/edit', [
            'name'=> $user->name,
            'email' => $user->email,
            'role' => 'user'
        ]);
        $response->assertStatus(200);
        $user = User::findOrFail($user->id)->delete();
    }
    public function test_deleteUser()
    {
        $response = $this->post('/login', [
            'email' => 'k1@gmail.com',
            'password' => 'Aditi@1230',
        ]);

        $this->assertAuthenticated();
        $user = User::factory()->create();

        $response = $this->call('DELETE', 'http://127.0.0.1:8000/user/' . $user->id, [
            $user
        ]);
        $response->assertStatus(302);
    }
    // public function test_unsubscribedUser()
    // {
    //     // $user = User::factory()->create();

    //     // $response = $this->get('http://127.0.0.1:8000/plans');
    //     // $response->assertStatus(500);
    // }
}
