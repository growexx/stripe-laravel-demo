<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    // use RefreshDatabase;



    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
        $user = User::findOrFail($user->id)->delete();
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/' . $notification->token);

            $response->assertStatus(200);

            return true;
        });
        $user = User::findOrFail($user->id)->delete();
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();
            return true;
        });
        $user = User::findOrFail($user->id)->delete();

    }

    protected function passwordEmailPostRoute()
    {
        return route('password.email');
    }

    protected function passwordEmailGetRoute()
    {
        return route('password.email');
    }

    public function test_UserReceivesAnEmailWithAPasswordResetLink()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'john12@example.com',
        ]);
        $response = $this->post($this->passwordEmailPostRoute(), [
            'email' => 'john12@example.com',
        ]);

        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
        $user = User::findOrFail($user->id)->delete();
    }

    public function testEmailIsRequired()
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), []);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
    }

    public function testEmailIsAValidEmail()
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'email' => 'invalid-email',
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
    }


    public function test_DoesNotSendPasswordResetEmail()
    {
        $this->doesntExpectJobs(ResetPassword::class);

        $this->post('password/email', ['email' => 'invalid@email.com']);
    }

    public function test_ChangesAUsersPassword()
    {
        $user = User::factory()->create();

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $user = User::findOrFail($user->id)->delete();

    }

    public function test_NewPassword()
    {
        $oldPassword = 'passwordd';
        $newPassword = 'newoneooo';

        $user = User::factory()->create(['password' => Hash::make($oldPassword)]);

        $this->actingAs($user);

        $response = $this->call('Post', '/reset-password', array(
            '_token' => csrf_token(),
            'current_password' => $oldPassword,
            'new_password' => $newPassword,
            'repeat_new_password' => $newPassword,
        ));
        $response->assertStatus(302);
        $this->assertFalse(Hash::check($newPassword, $user->password));
        $user = User::findOrFail($user->id)->delete();

    }
}
