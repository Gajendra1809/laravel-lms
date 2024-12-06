<?php

namespace App\Livewire\Pulse;

use Livewire\Component;
use App\Models\Book;

class TopBorrowedBooks extends Component
{
    public function render()
    {
        return view('livewire.pulse.top-borrowed-books');
    }

    public function getTopBorrowedBooks()
    {
        return Book::withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->take(15)
            ->get();
    }
}
