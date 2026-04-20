<?php

namespace App\Rules;

use App\Models\Keyword;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MetDataKeywordFormRule implements ValidationRule
{
    public function __construct(protected bool $is_update, protected int $meta_data_id, protected int|null $keyword_id){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {   
        if ($this->is_update) {
            $keyword_exists = Keyword::where('meta_data_id', $this->meta_data_id)
                ->where('id', '!=', $this->keyword_id)
                ->where('slug', $value)
                ->exists();
            
            if ($keyword_exists) {
                $fail('The keyword already exists');
            }
        } else {
            $keywords = Keyword::where('meta_data_id', $this->meta_data_id)->count();
    
            if ($keywords >= 3) {
                $fail('The keyword max limit is 3');
            }
            
            $keywords = Keyword::where('meta_data_id', $this->meta_data_id)->where('slug', $value)->exists();
    
            if ($keywords) {
                $fail('The keyword already exists');
            }
        }

    }
}
