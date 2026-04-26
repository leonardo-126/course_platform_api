<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class HasOneCorrectOption implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            return;
        }
        $correctCount = collect($value)
            ->filter(fn($opt) => ($opt['is_correct'] ?? false) === true)
            ->count();
        if ($correctCount === 0) {
            $fail('A questão precisa ter pelo menos 1 opção correta.');
        }
        if ($correctCount > 1) {
            $fail('Questões single_choice devem ter exatamente 1 opção correta.');
        }
    }
}
