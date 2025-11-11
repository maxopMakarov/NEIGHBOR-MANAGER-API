<?php
namespace App\Http\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserMetamaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'eth_address' => 'nullable|string|size:42,eth_address',
            'message' => 'nullable|string',
            'signature' => 'nullable|string',
        ];
    }
}