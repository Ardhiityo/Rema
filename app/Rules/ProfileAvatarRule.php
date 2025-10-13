<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ProfileAvatarRule implements ValidationRule
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

        if (is_null($user->avatar)) {
            $rules[$attribute] = ['file', 'mimes:jpg,png', 'max:1000'];
        } else {
            $rules[$attribute] = ['nullable', 'file', 'mimes:jpg,png', 'max:1000'];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->get($attribute) as $message) {
                $fail($message);
            }
        }
    }
}
