<?php

namespace App\Repositories\Contratcs;

use App\Data\Keyword\CreateKeywordData;
use App\Data\Keyword\KeywordData;
use App\Data\Keyword\UpdateKeywordData;
use Spatie\LaravelData\DataCollection;
use Throwable;

interface KeywordRepositoryInterface
{
    public function findByMetaDataId(int $meta_data_id): DataCollection;
    public function create(CreateKeywordData $data): KeywordData|Throwable;
    public function findByMetaDataIdAndKeywordSlug(int $meta_data_id, string $keyword_slug): KeywordData|Throwable;
    public function update(int $keyword_id, UpdateKeywordData $update_keyword_data): KeywordData|Throwable;
    public function delete(int $keyword_id): bool|Throwable;
}
