<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faculty page', function () {
    faculty()
        ->assertStatus(200)
        ->assertViewIs('livewire.faculty.index')
        ->assertSeeLivewire('faculty.faculty-form')
        ->assertSeeLivewire('faculty.faculty-list');
});