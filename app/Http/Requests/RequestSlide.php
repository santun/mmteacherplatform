<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestSlide extends FormRequest
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
            'title' => 'required',
            'weight' => 'integer|min:0',
			'uploaded_file' => 'required',
            //'title' => 'required|unique:slides,title,'. $this->route('slide') . ',id',
        ];
    }
}
