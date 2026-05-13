<?php

use App\Models\Faculty;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
    $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
    actingAs($admin);
});

test('staff form page', function () {
    staffForm()
        ->assertStatus(200)
        ->assertViewIs('livewire.staff.form');
});

test('it can create a staff member', function () {
    $faculty = Faculty::first();

    staffForm()
        ->set('name', 'John Doe')
        ->set('email', 'john.doe@gmail.com')
        ->set('password', 'Password123!')
        ->set('faculty_id', $faculty->id)
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-staff')
        ->assertSee('The staff was successfully created.');

    expect(User::where('email', 'john.doe@gmail.com')->exists())->toBeTrue()
        ->and(Staff::where('faculty_id', $faculty->id)->exists())->toBeTrue();
});

test('it validates required fields', function () {
    staffForm()
        ->call('create')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'faculty_id' => 'required',
        ]);
});

test('it validates unique email', function () {
    $staff = User::where('email', 'staff@gmail.com')->first();
    $faculty = Faculty::first();
    
    staffForm()
        ->set('email', $staff->email)
        ->set('name', 'John Doe')
        ->set('password', 'Password123!')
        ->set('faculty_id', $faculty->id)
        ->call('create')
        ->assertHasErrors(['email' => 'unique']);
});

test('it can load staff data for editing', function () {
    $faculty = Faculty::first();
    $user = User::factory()->create(['name' => 'Original Name']);
    $staff = Staff::create([
        'user_id' => $user->id,
        'faculty_id' => $faculty->id,
    ]);

    staffForm()
        ->dispatch('staff-edit', staff_id: $staff->id)
        ->assertSet('staff_id', $staff->id)
        ->assertSet('faculty_id', $faculty->id)
        ->assertSet('name', $user->name)
        ->assertSet('email', $user->email)
        ->assertSet('is_update', true);
});

test('it can update a staff member', function () {
    $faculty = Faculty::first();
    $user = User::where('email', 'staff@gmail.com')->first();
    $staff = $user->staff()->create([
        'faculty_id' => $faculty->id,
    ]);
    
    staffForm()
        ->set('staff_id', $staff->id)
        ->set('user_id', $user->id)
        ->set('is_update', true)
        ->set('name', 'Updated Name')
        ->set('email', 'updated@gmail.com')
        ->set('faculty_id', $faculty->id)
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-staff')
        ->assertSee('The staff was successfully updated.');

    expect($user->fresh()->name)->toBe('Updated Name')
        ->and($user->fresh()->email)->toBe('updated@gmail.com');
});

test('it can confirm deletion', function () {
    $faculty = Faculty::first();
    $user = User::factory()->create();
    $staff = Staff::create([
        'user_id' => $user->id,
        'faculty_id' => $faculty->id,
    ]);

    staffForm()
        ->dispatch('staff-delete-confirm', staff_id: $staff->id)
        ->assertSet('staff_id', $staff->id)
        ->assertSet('user_id', $user->id);
});

test('it can delete a staff member', function () {
    $faculty = Faculty::first();
    $user = User::where('email', 'staff@gmail.com')->first();
    $user->staff()->create([
        'faculty_id' => $faculty->id,
    ]);

    staffForm()
        ->set('user_id', $user->id)
        ->call('delete')
        ->assertDispatched('refresh-staff')
        ->assertSee('The staff was successfully deleted.');

    expect(User::find($user->id))->toBeNull()
        ->and(Staff::where('user_id', $user->id)->exists())->toBeFalse();
});

test('it can reset input fields', function () {
    staffForm()
        ->set('name', 'John')
        ->set('email', 'john@example.com')
        ->set('is_update', true)
        ->call('resetInput')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('is_update', false);
});
