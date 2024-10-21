<?php

namespace App\Http\Requests\BookRequests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class BookCreateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'author' => ['required', 'string', 'max:100'],
            'isbn' => ['required', 'string', 'max:17', 'unique:books'],
            'published_date' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'available' => [Rule::in(['1', '0'])],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Title must be less than 100 characters',
            'author.max' => 'Author name must be less than 100 characters',
            'isbn.max' => 'ISBN must be less than 17 characters',
            'isbn.unique' => 'ISBN already exists',
            'published_date.required' => 'Published date is required',
            'published_date.date' => 'Published date must be a valid date',
            'published_date.date_format' => 'Published date must be a valid date format (yyyy-m-d)',
            'published_date.before' => 'Published date must be before today',
            'available.in' => 'Available must be "1"=>Available, "0"=>Unavailable',
        ];
    }
}
