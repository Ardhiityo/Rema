<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Metadata;
use App\Models\StudyProgram;
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

        Metadata::create([
            'title' => $title,
            'author_name' => $author->user->name,
            'author_nim' => $author->nim,
            'study_program_id' => StudyProgram::first()->id,
            'author_id' => $author->id,
            'visibility' => 'public',
            'year' => '2025',
            'slug' => Str::slug($title),
            'status' => 'process',
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
