<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfileStudyProgramRule implements ValidationRule
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

        if ($user->hasRole('admin')) {
            $rules[$attribute] = ['nullable'];
        }

        if ($user->hasRole('author')) {
            $rules[$attribute] = ['required', 'exists:study_programs,id'];
        }

        $validator = Validator::make(data: $data, rules: $rules, attributes: [$attribute => 'study program']);

        if ($validator->fails()) {
            foreach ($validator->errors()->get($attribute) as $message) {
                $fail($message);
            }
        }
    }
}
