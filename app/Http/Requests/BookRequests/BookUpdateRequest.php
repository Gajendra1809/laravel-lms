<?php

namespace App\Http\Requests\BookRequests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class BookUpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $uuid = $this->route('uuid');

        return [
            'title' => ['string', 'max:100'],
            'author' => ['string', 'max:100'],
            'isbn' => ['string', 'max:17', Rule::unique('books')->ignore($uuid, 'uuid')],
            'published_date' => ['date', 'date_format:Y-m-d', 'before:today'],
            'available' => [Rule::in(['1', '0'])],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'The title may not be greater than 100 characters.',
            'author.max' => 'The author may not be greater than 100 characters.',
            'isbn.max' => 'The ISBN may not be greater than 17 characters.',
            'isbn.unique' => 'The ISBN has already been taken.',
            'published_date.date_format' => 'The published date must be a valid date format (yyyy-m-d).',
            'published_date.before' => 'The published date must be before today.',
            'available.in' => 'Available must be "1"=>Available, "0"=>Unavailable',
        ];
    }

}
