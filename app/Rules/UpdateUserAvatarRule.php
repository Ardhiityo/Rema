<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use App\Data\User\UserData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class UpdateUserAvatarRule implements ValidationRule
{
    protected bool $isRequired;

    public function __construct(
        protected int $user_id,
        protected int $max_KB = 1000,
        protected array $allowedMimes = ['jpg', 'png']
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::findOrFail($this->user_id);

        if (is_null($user->avatar)) {
            $fail('The avatar field is required.');
        }

        if ($value instanceof UploadedFile) {
            $size = $value->getSize();
            $mime = $value->getClientOriginalExtension();

            if (!in_array(strtolower($mime), $this->allowedMimes)) {
                $fail("The file must be of type: " . implode(', ', $this->allowedMimes) . ".");
            }

            if ($size > $this->max_KB * 1024) {
                $fail("The {$attribute} field must not be greater than {$this->max_KB} kilobytes.");
            }
        }
    }
}
