<?php

namespace App\Http\Requests;

class StoreBookRequest extends BookRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->rulesWithPresence('required');
    }
}
