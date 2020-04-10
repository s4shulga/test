<?php

namespace App\Http\Requests\API\Profile;

use Dingo\Api\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|max:191',
            'image'        => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
        ];
    }
}
