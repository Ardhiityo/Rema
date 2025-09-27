<?php

namespace App\Rules;

use Closure;
use App\Models\Author;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class AuthorUpdateRule implements ValidationRule
{
    public function __construct(public bool $is_update, public int|null $author_id) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->is_update) {
            $is_exists = Author::where('id', '!=', $this->author_id)
                ->where('nim', $value)->exists();
            if ($is_exists) {
                $fail('The nim has already been taken.');
            }
        } else {
            $is_exists = Author::where('nim', $value)->exists();
            if ($is_exists) {
                $fail('The nim has already been taken.');
            }
        }
    }
}
