<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StoreBookRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'isbn' => 'required|string|between:13,100',
            'title' => 'required|string|max:100',
            'subtitle' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'published' => 'nullable|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'pages' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'website' => 'nullable|string',
            'user_id' => 'nullable|string'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }


    public function messages()
    {
        return [
            'isbn.required' => 'ISBN is required',
            'title.required' => 'Title is required',

        ];
    }
}
