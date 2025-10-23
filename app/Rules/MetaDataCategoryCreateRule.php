<?php

namespace App\Rules;

use Closure;
use App\Models\MetaDataCategory;
use Illuminate\Contracts\Validation\ValidationRule;

class MetaDataCategoryCreateRule implements ValidationRule
{
    public function __construct(public int $meta_data_id) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $repository_already_exists = MetaDataCategory::where('meta_data_id', $this->meta_data_id)
            ->where('category_id', $value)->exists();

        if ($repository_already_exists) {
            $fail("The category field is already exists.");
        }
    }
}
