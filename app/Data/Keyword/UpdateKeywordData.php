<?php

declare(strict_types=1);

namespace App\Data\Keyword;

use Livewire\Attributes\Computed;
use Spatie\LaravelData\Data;

class UpdateKeywordData extends Data
{
    #[Computed()]
    public string $name_formatted;

    public function __construct(
        public string $name,
        public string $slug
    ) {
        $this->name_formatted = trim(ucfirst(strtolower($this->name)));
    }
}
