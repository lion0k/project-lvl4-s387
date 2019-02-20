<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use SimpleTaskManager\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testWorkApplication()
    {
        $this->get(route('index'))->assertStatus(Response::HTTP_OK);
    }

    public function testGetUserRegistrationForm()
    {
        $this->get(route('register'))->assertStatus(Response::HTTP_OK);
    }

    public function testGetUserLoginForm()
    {
        $this->get(route('login'))->assertStatus(Response::HTTP_OK);
    }

    public function testLoginAndLogoutUser()
    {
        $this->assertGuest();
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('login'))->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('index'));
        $this->assertGuest();
    }

    public function testCreateUserProfile()
    {
        $password = Hash::make('you know nothing');

        $userData = [
        'name' => 'John Snow',
        'email' => 'john@winterfell.com',
        'password' => $password,
        'password_confirmation' => $password,
        ];

        $this->post(route('register'), $userData)->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function testUpdateUserProfile()
    {
        $user = factory(User::class)->create();
        $newEmail = 'john@winterfell.com';

        $this->actingAs($user)->get(route('home'))->assertStatus(Response::HTTP_OK);

        $this->actingAs($user)->post(route('user.update'), [
            'name' => $user->name,
            'email' => $newEmail,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            '_method' => 'PATCH',
            '_token' => csrf_token()
        ])->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => $newEmail]);
    }

    public function testDeleteUserProfile()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get(route('home'))->assertStatus(Response::HTTP_OK);

        $this->actingAs($user)->post(route('user.delete'), [
            '_method' => 'DELETE',
            '_token' => csrf_token()
        ])->assertRedirect(route('index'));
        $this->assertDatabaseMissing('users', ['email' => $user->email]);
    }
}
