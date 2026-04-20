<?php

declare(strict_types=1);

namespace App\Data\Keyword;

use Livewire\Attributes\Computed;
use Spatie\LaravelData\Data;

class CreateKeywordData extends Data
{
    #[Computed()]
    public string $name_formatted;

    public function __construct(
        public int|string $meta_data_id,
        public string $name,
        public string $slug
    ) {
        $this->name_formatted = ucfirst(strtolower($this->name));
    }
}
