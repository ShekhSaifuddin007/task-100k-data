<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class CSVRowRule implements Rule
{
    public function passes($attribute, $value)
    {
        $data = [];
        while( ($line = fgetcsv(file($value))) !== false) {
            $data[] = $line;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
