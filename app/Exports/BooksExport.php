<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    
    /**
     * Returns a collection of books.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Book>
     */
    public function collection()
    {
        return Book::all();
    }

    /**
     * Define the headings for the export.
     *
     * The headings are the field names for the export.
     *
     * @return array The headings for the export.
     */
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
