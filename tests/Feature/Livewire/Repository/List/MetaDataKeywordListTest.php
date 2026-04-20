<?php

use App\Data\Keyword\KeywordData;
use App\Models\Keyword;
use App\Models\MetaData;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataKeywordList(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id);
});

test('get keywords success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();
    
    $keyword = KeywordData::fromModel(Keyword::first());
    
    $component = metaDataKeywordList(
        [
            'meta_data_id' => $meta_data->id
        ])
        ->assertSet('meta_data_id', $meta_data->id)
        ->assertSeeText($keyword->name);
    
    expect(1)->toBe(count($component->instance()->keywords()));
});
