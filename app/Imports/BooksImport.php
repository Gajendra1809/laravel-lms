<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;

class BooksImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Book([
            'title' => $row['title'],                 // Using the header row to map 'title'
            'author' => $row['author'],               // Using the header row to map 'author'
            'isbn' => $row['isbn'],                   // Using the header row to map 'isbn'
            'published_date' => $row['published_date'], // Using the header row to map 'published_date'
            'admin_id' => auth()->id(),
        ]);
    }
    
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
}
