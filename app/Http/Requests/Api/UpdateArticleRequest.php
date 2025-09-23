<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes','string','max:200'],
            'body'  => ['sometimes','string'],
            'category_ids'   => ['nullable','array'],
            'category_ids.*' => ['integer','exists:categories,id'],
            'tag_ids'        => ['nullable','array'],
            'tag_ids.*'      => ['integer','exists:tags,id'],
            
        ];
    }
}
