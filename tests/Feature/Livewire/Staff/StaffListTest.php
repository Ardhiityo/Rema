<?php

use App\Models\Faculty;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\FacultySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('staff list page', function () {
    staffList()
        ->assertStatus(200)
        ->assertViewIs('livewire.staff.list');
});

test('it lists staff members', function () {
    $this->seed(FacultySeeder::class);
    $faculty = Faculty::first();
    $user = User::factory()->create(['name' => 'Visible Staff']);
    Staff::create([
        'user_id' => $user->id,
        'faculty_id' => $faculty->id,
    ]);

    staffList()
        ->assertSee('Visible Staff');
});

test('it can search staff by keyword', function () {
    $this->seed(FacultySeeder::class);
    $faculty = Faculty::first();
    
    $user1 = User::factory()->create(['name' => 'Searchable User']);
    Staff::create(['user_id' => $user1->id, 'faculty_id' => $faculty->id]);
    
    $user2 = User::factory()->create(['name' => 'Other User']);
    Staff::create(['user_id' => $user2->id, 'faculty_id' => $faculty->id]);

    staffList()
        ->set('keyword', 'Searchable')
        ->assertSee('Searchable User')
        ->assertDontSee('Other User');
});

test('it can filter staff by faculty', function () {
    $this->seed(FacultySeeder::class);
    $faculty1 = Faculty::first();
    
    // Create second faculty
    $faculty2 = Faculty::create([
        'name' => 'Engineering Faculty',
        'slug' => 'engineering-faculty',
    ]);

    $user1 = User::factory()->create(['name' => 'Faculty 1 Staff']);
    Staff::create(['user_id' => $user1->id, 'faculty_id' => $faculty1->id]);

    $user2 = User::factory()->create(['name' => 'Engineering Staff']);
    Staff::create(['user_id' => $user2->id, 'faculty_id' => $faculty2->id]);

    staffList()
        ->set('faculty_slug', $faculty2->slug)
        ->assertSee('Engineering Staff')
        ->assertDontSee('Faculty 1 Staff');
});

test('it can reset search filters', function () {
    staffList()
        ->set('keyword', 'some search')
        ->set('faculty_slug', 'some-faculty')
        ->call('resetInput')
        ->assertSet('keyword', '')
        ->assertSet('faculty_slug', '');
});

test('it responds to refresh-staff event', function () {
    staffList()
        ->dispatch('refresh-staff')
        ->assertStatus(200);
});