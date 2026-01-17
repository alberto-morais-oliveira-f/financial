<?php

namespace Am2tec\Financial\Application\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization logic is handled by the controller's policy.
        return true;
    }

    public function rules(): array
    {
        return [
            'from_wallet_id' => ['required', 'uuid'],
            'to_wallet_id' => ['required', 'uuid'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
