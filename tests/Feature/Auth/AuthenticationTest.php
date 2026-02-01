<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    expect($user->email)->not()->toBeNull();
    expect($user->password)->not()->toBeNull();

    Turnstile::fake();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'rahasia',
        'cf-turnstile-response' => 'fake-token'
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    expect($user->email)->not()->toBeNull();
    expect($user->password)->not()->toBeNull();

    Turnstile::fake();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        'cf-turnstile-response' => 'fake-token'
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
