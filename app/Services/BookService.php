<?php

namespace App\Services;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\Gate;

class BookService
{
    public function __construct(
        protected BookRepository $bookRepository
    ){
    }

    public function all(){
        return $this->bookRepository->all();
    }

    public function create(array $data){
        $data['admin_id'] = auth()->id();
        return $this->bookRepository->create($data);
    }

    public function getBookById(int $id){
        $book = $this->bookRepository->find($id);
        if($book==null) {
            return false;
        }
        return $book;
    }

    public function getBook(string $uuid){
        return $this->bookRepository->findByUuid($uuid);
    }

    public function updateByUuid(string $uuid, array $data){
        $book = $this->getBook($uuid);
        if(!$book) {
            return false;
        }
        $this->authorize($book);
        return $this->bookRepository->update($uuid, $data);
    }

    public function deleteByUuid(string $uuid){
        $book = $this->getBook($uuid);
        if(!$book) {
            return false;
        }
        $this->authorize($book);
        return $this->bookRepository->delete($uuid);
    }

    public function search($query){
        return $this->bookRepository->search($query);
    }

    public function authorize($book){
        Gate::authorize('updateDel', $book);
    }

    public function exportAllBooks(){
        return $this->bookRepository->exportAll(new BooksExport, 'books.xlsx');
    }

    public function importBooks(){
        return $this->bookRepository->importData(new BooksImport, request()->file('file'));
    }
}
