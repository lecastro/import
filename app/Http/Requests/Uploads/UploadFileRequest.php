<?php

declare(strict_types=1);

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filename' => 'required|file|mimes:csv,txt',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
