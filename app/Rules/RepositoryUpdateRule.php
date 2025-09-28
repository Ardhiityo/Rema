<?php

namespace App\Rules;

use App\Models\Repository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RepositoryUpdateRule implements ValidationRule
{
    public function __construct(public bool $is_update, public int|null $repository_id) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->is_update) {
            $is_exists = Repository::where('id', '!=', $this->repository_id)
                ->where('slug', $value)->exists();
            if ($is_exists) {
                $fail('The title has already been taken.');
            }
        } else {
            $is_exists = Repository::where('slug', $value)->exists();
            if ($is_exists) {
                $fail('The title has already been taken.');
            }
        }
    }
}
