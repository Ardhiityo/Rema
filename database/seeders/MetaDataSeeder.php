<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\MetaData;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

use function Symfony\Component\Clock\now;

class MetaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = fake()->sentence();
        $author = Author::where('nim', 22040004)->first();

        MetaData::create([
            'title' => $title,
            'author_id' => $author->id,
            'visibility' => 'public',
            'year' => '2025',
            'slug' => Str::slug($title),
            'status' => 'pending',
            'created_at' => now()
        ])->categories()->attach(Category::first()->id, [
            'file_path' => UploadedFile::fake()
                ->create(
                    'testing.pdf',
                    200,
                    'application/pdf'
                )->store('repositories', 'public')
        ]);
    }
}
