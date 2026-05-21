<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetReportsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:pending,in_review,resolved,dismissed'],
            'type' => ['nullable', 'string', 'in:spam,harassment,inappropriate,copyright,misinformation'],
            'withReportCountForReportedUser' => ['nullable', 'boolean'],
        ];
    }
}
