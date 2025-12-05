<?php

use App\Livewire\MetaDataCategoryForm;
use App\Models\User;
use App\Models\Category;
use App\Models\MetaData;
use App\Models\MetaDataCategory;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use App\Rules\MetaDataCategoryCreateRule;
use App\Rules\MetaDataCategoryUpdateRule;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mount create success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::where('email', 'admin@gmail.com')->first();

    actingAs($user);

    metaDataCategoryForm()
        ->assertSet('meta_data_id', '');
});

test('mount update success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::where('email', 'admin@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id);
});

test('title create success', function () {
    $component = metaDataCategoryForm();

    expect('Create Repository')
        ->toBe($component->instance()->title());
});


test('title edit success', function () {
    $component = metaDataCategoryForm()
        ->set('is_update', true);

    expect('Edit Repository')
        ->toBe($component->instance()->title());
});

test('create repository success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $category = Category::where('slug', 'journal')->first();

    expect('journal')->toBe($category->slug);

    expect($meta_data)->not()->toBeNull();

    $meta_data = MetaData::first();

    $file = UploadedFile::fake()->createWithContent(
        'repo.pdf',
        file_get_contents(base_path('tests/Assets/sample.pdf')),
    );

    metaDataCategoryForm(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id)
        ->set('category_id', $category->id)
        ->set('file_path', $file)
        ->call('createRepository')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-repository-table')
        ->assertSet('file_path', '')
        ->assertSet('category_id', '')
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('meta_data_category', 2);

    $this->assertDatabaseHas('meta_data_category', [
        'meta_data_id' => $meta_data->id,
        'category_id' => $category->id,
    ]);
});

test('create repository failed validation', function () {
    $this->markTestIncomplete();
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $category = Category::where('slug', 'journal')->first();

    expect('journal')->toBe($category->slug);

    expect(1)->toBe($meta_data->id);

    $meta_data = MetaData::first();

    $file = UploadedFile::fake()->createWithContent(
        'repo.pdf',
        file_get_contents(base_path('tests/Assets/sample.pdf')),
    );

    metaDataCategoryForm(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id)
        ->set('category_id', Category::first()->id)
        ->set('file_path', $file)
        ->call('createRepository')
        ->assertHasErrors([
            'category_id' => MetaDataCategoryCreateRule::class
        ])
        ->assertNotDispatched('refresh-repository-table')
        ->assertNotSet('file_path', '')
        ->assertNotSet('category_id', '')
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('meta_data_category', 1);

    $this->assertDatabaseMissing('meta_data_category', [
        'meta_data_id' => $meta_data->id,
        'category_id' => $category->id,
    ]);
});

test('edit repository success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->call(
            'editRepository',
            $meta_data_category->metadata->slug,
            $meta_data_category->category->slug
        )
        ->assertSet(
            'meta_data_id',
            $meta_data_category->meta_data_id
        )
        ->assertSet(
            'category_id',
            $meta_data_category->category_id
        )
        ->assertSet(
            'category_id_update',
            $meta_data_category->category_id
        )
        ->assertSet(
            'file_path_update',
            $meta_data_category->file_path
        )->assertSet('is_update', true);
});

test('edit repository failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->call(
            'editRepository',
            $meta_data_category->metadata->slug,
            'salah'
        )
        ->assertSet(
            'meta_data_id',
            $meta_data_category->meta_data_id
        )
        ->assertNotSet(
            'category_id',
            $meta_data_category->category_id
        )
        ->assertNotSet(
            'category_id_update',
            $meta_data_category->category_id
        )
        ->assertNotSet(
            'file_path_update',
            $meta_data_category->file_path
        )->assertNotSet('is_update', true);
});

test('update repository success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $file = UploadedFile::fake()->createWithContent('repo.pdf', file_get_contents(base_path('tests/Assets/sample.pdf')));

    $meta_data_category = MetaDataCategory::first();
    $new_category = Category::whereSlug('journal')->first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->set('category_id', $new_category->id)
        ->set('category_id_update', $meta_data_category->category->id)
        ->set('file_path', $file)
        ->assertSet(
            'meta_data_id',
            $meta_data_category->meta_data_id
        )
        ->assertSet(
            'category_id',
            $new_category->id
        )
        ->assertSet(
            'category_id_update',
            $meta_data_category->category_id
        )
        ->call(
            'updateRepository',
        )
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-repository-table')
        ->assertSet('file_path', null)
        ->assertSet('category_id', '');

    expect($new_category->id)
        ->toBe(MetaDataCategory::first()->category_id);

    expect(MetaDataCategory::first()->file_path)->not()
        ->toBe($meta_data_category->file_path);

    $this->assertDatabaseCount('meta_data_category', 1);

    $this->assertDatabaseHas('meta_data_category', [
        'meta_data_id' => $meta_data_category->meta_data_id,
        'category_id' => $new_category->id
    ]);
});

test('update repository failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $file = UploadedFile::fake()->createWithContent('repo.pdf', file_get_contents(base_path('tests/Assets/sample.pdf')));

    $meta_data_category = MetaDataCategory::first();
    $new_category = Category::whereSlug('journal')->first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->set('category_id', 100)
        ->set('category_id_update', $meta_data_category->category_id)
        ->set('file_path', $file)
        ->assertSet(
            'meta_data_id',
            $meta_data_category->meta_data_id
        )
        ->assertSet(
            'category_id',
            100
        )
        ->assertSet(
            'category_id_update',
            $meta_data_category->category_id
        )
        ->call(
            'updateRepository',
        )
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-repository-table')
        ->assertNotSet('file_path', null)
        ->assertNotSet('category_id', '');

    expect(100)->not()
        ->toBe(MetaDataCategory::first()->category_id);

    $this->assertDatabaseCount('meta_data_category', 1);

    $this->assertDatabaseMissing('meta_data_category', [
        'meta_data_id' => $meta_data_category->meta_data_id,
        'category_id' => 100,
    ]);
});

test('delete confirm success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->assertSet('meta_data_id', $meta_data_category->meta_data_id)
        ->call('deleteConfirm', $meta_data_category->metadata->slug, $meta_data_category->category->slug)
        ->assertSet('category_id_delete', $meta_data_category->category_id);;
});

test('delete confirm failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->assertSet('meta_data_id', $meta_data_category->meta_data_id)
        ->call('deleteConfirm', $meta_data_category->metadata->slug, 100)
        ->assertNotSet('category_id_delete', $meta_data_category->category_id);
});

test('delete success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->assertSet('meta_data_id', $meta_data_category->meta_data_id)
        ->set('category_id_delete', $meta_data_category->category_id)
        ->call('delete')
        ->assertDispatched('refresh-repository-table')
        ->assertSet('category_id', '')
        ->assertSet('file_path', null)
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('meta_data_category', 0);
    $this->assertDatabaseMissing('meta_data_category', [
        'meta_data_id' => $meta_data_category->meta_data_id,
        'category_id' => $meta_data_category->category_id,
        'file_path' => $meta_data_category->file_path
    ]);
});

test('delete failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data_category = MetaDataCategory::first();

    metaDataCategoryForm(['meta_data_id' => $meta_data_category->meta_data_id])
        ->assertSet('meta_data_id', $meta_data_category->meta_data_id)
        ->set('category_id_delete', 100)
        ->call('delete')
        ->assertNotDispatched('refresh-repository-table');

    $this->assertDatabaseCount('meta_data_category', 1);
    $this->assertDatabaseHas('meta_data_category', [
        'meta_data_id' => $meta_data_category->meta_data_id,
        'category_id' => $meta_data_category->category_id,
        'file_path' => $meta_data_category->file_path
    ]);
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    metaDataCategoryForm()
        ->set('file_path', '123')
        ->set('category_id', 1)
        ->set('is_update', true)
        ->call('resetInput')
        ->assertSet('file_path', null)
        ->assertSet('category_id', '')
        ->assertSet('is_update', false);
});
