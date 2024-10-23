<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Book::all();
    }

    public function headings(): array
    {
        return [
            'ID',            // ID of the book
            'UUID',          // UUID of the book
            'Title',         // Book title
            'Author',        // Author of the book
            'ISBN no.',      // International Standard Book Number
            'Published At',  // Date the book was published
            'Availability',  // Availability of the book
            'Admin Id',      // ID of the admin who created the book
            'Deleted At',    // Timestamp when the record was deleted
            'Created At',    // Timestamp when the record was created
            'Updated At',    // Timestamp when the record was updated
        ];
    }
}
