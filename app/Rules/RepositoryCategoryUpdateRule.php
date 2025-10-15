<?php

namespace App\Rules;

use Closure;
use App\Models\Repository;
use Illuminate\Contracts\Validation\ValidationRule;

class RepositoryCategoryUpdateRule implements ValidationRule
{
    public function __construct(public int $meta_data_id, public int $category_id_update) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Repository::where('meta_data_id', $this->meta_data_id)
            ->where('category_id', '!=', $this->category_id_update)
            ->where('category_id', $value)
            ->exists();

        if ($exists) {
            $fail('The category field is already exists');
        }
    }
}
