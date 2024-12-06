<x-pulse::card >
    <x-pulse::card-header name="Top Borrowed Books">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 8h18M3 13h18M3 18h18" />
            </svg>
        </x-slot:icon>
    </x-pulse::card-header>

    <x-pulse::scroll>
        <ul>
            @foreach ($this->getTopBorrowedBooks() as $book)
                <li>{{ $book->title }} - Borrowed {{ $book->borrows_count }} times</li>
            @endforeach
        </ul>
    </x-pulse::scroll>
</x-pulse::card>
