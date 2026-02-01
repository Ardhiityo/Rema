<?php

use Illuminate\Support\Facades\Storage;
use Database\Seeders\RolePermissionSeeder;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    Storage::fake('public');

    $this->seed(RolePermissionSeeder::class);

    Turnstile::fake();

    $response = $this->post(route('register'), [
        'name' => 'Arya Adhi Prasetyo',
        'email' => 'aryaadi229@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'cf-turnstile-response' => 'fake-token',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
