<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('form title', function () {
    // is_update = true
    $component = categoryForm()
        ->set('is_update', true)
        ->assertSet('is_update', true);

    $this->assertEquals('Edit Category', $component->formTitle);

    // is_update = false
    $component =  categoryForm()
        ->set('is_update', false)
        ->assertSet('is_update', false);

    $this->assertEquals('Create New Category', $component->formTitle);
});

test('create success', function () {
    categoryForm()
        ->set('name', 'Cyber Security')
        ->assertSet('name', 'Cyber Security')
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('categories', [
        'name' => 'Cyber Security',
        'slug' => Str::slug('Cyber Security')
    ]);
});

test('create failed validation', function () {
    categoryForm()
        ->set('name', '')
        ->assertSet('name', '')
        ->call('create')
        ->assertHasErrors([
            'name' => 'required',
            'slug' => 'required'
        ]);

    $this->assertDatabaseCount('categories', 0);
});

test('create failed validation already exists', function () {
    $this->seed(DatabaseSeeder::class);

    categoryForm()
        ->set('name', 'Teknik Informatika')
        ->assertSet('name', 'Teknik Informatika')
        ->call('create')
        ->assertHasErrors([
            'slug' => 'unique'
        ]);

    $this->assertDatabaseCount('categories', 1);

    $this->assertDatabaseHas('categories', [
        'name' => 'Teknik Informatika',
        'slug' => Str::slug('Teknik Informatika')
    ]);
});

test('edit success', function () {
    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    categoryForm()
        ->call('edit', $category->id)
        ->assertSet('name', $category->name)
        ->assertSet('category_id', $category->id)
        ->assertSet('is_update', true);
});

test('edit failed not found', function () {
    categoryForm()->call('edit', 1)
        ->assertSet('is_update', false);
});

test('update success', function () {
    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    categoryForm()
        ->set('category_id', $category->id)
        ->set('name', 'Cyber Security')
        ->call('update')
        ->assertDispatched('refresh-categories');

    $this->assertDatabaseCount('categories', 1);

    $this->assertDatabaseHas('categories', [
        'name' => 'Cyber Security',
        'slug' => Str::slug('Cyber Security')
    ]);

    $this->assertDatabaseMissing('categories', [
        'name' => 'Teknik Informatika',
        'slug' => Str::slug('Teknik Informatika')
    ]);
});

test('update failed not found', function () {
    categoryForm()
        ->set('category_id', 1)
        ->set('name', 'Cyber Security')
        ->call('update')
        ->assertNotDispatched('refresh-categories');

    $this->assertDatabaseCount('categories', 0);

    $this->assertDatabaseMissing('categories', [
        'name' => 'Cyber Security',
        'slug' => Str::slug('Cyber Security')
    ]);
});


test('delete confirm success', function () {
    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    categoryForm()->call('deleteConfirm', $category->id)
        ->assertSet('category_id', $category->id);
});

test('delete confirm not found', function () {
    categoryForm()->call('deleteConfirm', 1)
        ->assertNotSet('category_id', 1);
});

test('delete success', function () {
    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    categoryForm()
        ->set('category_id', $category->id)
        ->call('delete')
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-categories');

    $this->assertDatabaseCount('categories', 0);
});

test('delete failed not found', function () {
    categoryForm()
        ->set('category_id', 1)
        ->call('delete')
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-categories');

    $this->assertDatabaseCount('categories', 0);
});

test('reset input success', function () {
    categoryForm()
        ->set('name', 'Cyber Security')
        ->set('slug', 'Cyber Security')
        ->set('keyword', 'Cyber Security')
        ->set('is_update', true)
        ->call('resetInput')
        ->assertNotSet('name', 'Cyber Security')
        ->assertNotSet('slug', 'Cyber Security')
        ->assertNotSet('keyword', 'Cyber Security')
        ->assertNotSet('is_update', true)
        ->assertSet('name', '')
        ->assertSet('slug', '')
        ->assertSet('keyword', '')
        ->assertSet('is_update', false);
});
