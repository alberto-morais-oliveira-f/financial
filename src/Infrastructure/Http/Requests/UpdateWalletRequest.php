<?php

namespace Am2tec\Financial\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Am2tec\Financial\Domain\Enums\WalletType;
use Illuminate\Validation\Rules\Enum;

class UpdateWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', new Enum(WalletType::class)],
            'balance' => ['sometimes', 'integer'],
            'meta' => ['nullable', 'array']
        ];
    }
}
