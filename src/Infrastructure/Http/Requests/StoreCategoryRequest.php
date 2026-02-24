<?php

namespace Am2tec\Financial\Infrastructure\Http\Requests;

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
            'name' => ['required', 'string', 'max:255'],
            'parent_uuid' => ['nullable', 'exists:fin_categories,uuid'],
            'type' => ['required', new Enum(CategoryType::class)],
            'description' => ['nullable', 'string'],
            'is_system_category' => ['boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_system_category' => $this->has('is_system_category'),
        ]);
    }
}
