<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfileNimRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();

        $data = [
            $attribute => $value
        ];

        $rules = [
            $attribute => []
        ];

        if ($user->hasRole('author')) {
            if (is_null($user->author->nim)) {
                $rules[$attribute] = ['numeric', 'digits_between:8,15', 'unique:authors,nim'];
            } else {
                $rules[$attribute] = ['numeric', 'digits_between:8,15', 'unique:authors,nim,' . $user->author->id];
            }
        }

        if ($user->hasRole('admin')) {
            $rules[$attribute] =  ['nullable'];
        }

        $validator = Validator::make(data: $data, rules: $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->get($attribute) as $message) {
                $fail($message);
            }
        }
    }
}
