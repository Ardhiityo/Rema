<?php

namespace App\Repositories\Eloquent;

use App\Data\Keyword\CreateKeywordData;
use App\Data\Keyword\KeywordData;
use App\Data\Keyword\UpdateKeywordData;
use App\Models\Keyword;
use App\Repositories\Contratcs\KeywordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\DataCollection;
use Throwable;

class KeywordRepository implements KeywordRepositoryInterface
{
    public function findByMetaDataId(int $meta_data_id): DataCollection
    {
        return KeywordData::collect(Keyword::where('meta_data_id', $meta_data_id)->get(), DataCollection::class);
    }

    public function create(CreateKeywordData $data): KeywordData|Throwable
    {
        try {
            $keyword = Keyword::create([
                'meta_data_id' => $data->meta_data_id,
                'name' => $data->name_formatted,
                'slug' => $data->slug,
            ]);

            return KeywordData::fromModel($keyword);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'KeywordRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_keyword_data' => $data,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findByMetaDataIdAndKeywordSlug(int $meta_data_id, string $keyword_slug): KeywordData|Throwable
    {
        try {
            $keyword = Keyword::where('meta_data_id', $meta_data_id)
                ->where('slug', $keyword_slug)
                ->firstOrFail();

            return KeywordData::fromModel($keyword);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'KeywordRepository',
                        'method' => 'findByMetaDataIdAndKeywordSlug',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                        'keyword_slug' => $keyword_slug,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update(int $keyword_id, UpdateKeywordData $data): KeywordData|Throwable
    {
        try {
            $keyword = Keyword::findOrFail($keyword_id);

            $keyword->update([
                'name' => $data->name_formatted,
                'slug' => $data->slug,
            ]);

            return KeywordData::fromModel($keyword);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'KeywordRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'keyword_id' => $keyword_id,
                        'update_keyword_data' => $data,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }
    
    public function delete(int $keyword_id): bool|Throwable
    {
        try {
            $keyword = Keyword::findOrFail($keyword_id);

            $keyword->delete();

            return true;
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'KeywordRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'keyword_id' => $keyword_id,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }
}
