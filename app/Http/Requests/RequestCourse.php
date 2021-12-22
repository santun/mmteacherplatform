<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestCourse extends FormRequest
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

        $rules = [
            /*'title' => 'required|unique:resources,title,'. $this->route('resource') . ',id',*/
            'title' => 'required',
            // 'slug' => 'required|unique:resources,slug,'. $this->route('resource') . ',id',
            'description' => 'required',
            'url_link' => 'nullable|url|max:255',
            'course_category_id' => 'required|integer|min:1',
            'level_id' => 'required|integer|min:1',
            'downloadable_option' => 'required|integer|min:1',
        ];
        
        if (Request::isMethod('post')) {
            $rules += ['cover_image' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg|max:5120'];
            $rules += ['resource_file' => 'file|mimes:zip,rar,docx,pdf|max:5242880'];
        }else{
            $rules += ['cover_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:5120'];
            $rules += ['resource_file' => 'file|mimes:zip,rar,docx,pdf|max:5242880'];
        }

        return $rules;
    }
}
