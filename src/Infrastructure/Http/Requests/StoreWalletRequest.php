<?php

namespace Am2tec\Financial\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Am2tec\Financial\Domain\Enums\WalletType;
use Illuminate\Validation\Rules\Enum;

class StoreWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(WalletType::class)],
            'balance' => ['required', 'integer'],
            'meta' => ['nullable', 'array']
        ];
    }
}
