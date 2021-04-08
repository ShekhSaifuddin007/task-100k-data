<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCSVRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return $value->getClientOriginalExtension() == 'csv';
    }

    public function message(): string
    {
        return __('validation.custom.csv_file_rule');
    }
}
