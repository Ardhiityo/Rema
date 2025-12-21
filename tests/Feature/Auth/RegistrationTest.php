<?php

use Illuminate\Support\Facades\Storage;
use Database\Seeders\RolePermissionSeeder;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    Storage::fake('public');

    $this->seed(RolePermissionSeeder::class);

    $response = $this->post(route('register'), [
        'name' => 'Arya Adhi Prasetyo',
        'email' => 'aryaadi229@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
