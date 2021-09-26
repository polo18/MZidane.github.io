<?php

namespace App\Http\Requests\Back;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $id = $this->method() === 'PUT' ? ',' . basename($this->url()) : '';
        
        return $rules = [
            'title' => 'required|max:255',
            'slug' => ['required', 'max:255', new Slug, 'unique:posts,slug' . $id],
        ];
    }
}
