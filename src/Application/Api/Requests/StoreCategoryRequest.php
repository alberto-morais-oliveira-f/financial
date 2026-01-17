<?php

namespace Am2tec\Financial\Application\Api\Requests;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_uuid' => ['nullable', 'uuid', 'exists:financial_categories,uuid'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(CategoryType::class)],
            'description' => ['nullable', 'string'],
        ];
    }
}
