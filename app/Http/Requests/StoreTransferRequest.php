<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Transfer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreTransferRequest
 * @package App\Http\Requests
 */
class StoreTransferRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Transfer::class, (int) $this->input('recipient_id')]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'digits_between:1,20'],
            'amount'       => ['required', 'integer'],
        ];
    }
}
