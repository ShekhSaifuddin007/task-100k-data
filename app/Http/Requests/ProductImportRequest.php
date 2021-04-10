<?php

namespace App\Http\Requests;

use App\Rules\CSVRowRule;
use App\Rules\ValidCSVRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'file', new ValidCSVRule]
        ];
    }
}
