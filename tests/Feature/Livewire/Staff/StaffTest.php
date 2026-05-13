<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('staff page', function () {
    staff()
        ->assertStatus(200)
        ->assertViewIs('livewire.staff.index')
        ->assertSeeLivewire('staff.staff-form')
        ->assertSeeLivewire('staff.staff-list');
});