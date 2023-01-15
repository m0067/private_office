<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRequest
 * @package App\Http\Requests
 */
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->user()->cant('create', [User::class, (string) $this->input('role'), (bool) $this->input('is_blocked')])) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $required = 'required';

        if ($this->isMethod('put')) {
            $required = 'nullable';
        }

        return [
            'name'     => [$required, 'string', 'max:255'],
            'email'    => [$required, 'string', 'email', 'max:255', 'unique:users'],
            'password' => [$required, 'string', 'min:8', 'confirmed'],
        ];
    }
}
