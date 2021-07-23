<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    //Pass in data because this is the sanitized data.
    public function store(){
        $book = Book::create($this->validateRequest());

        return redirect($book->path());
    }

    public function update(Book $book){
       $book->update($this->validateRequest());
       return redirect($book->path());
    }

    public function destroy(Book $book){
        $book->delete();
        return redirect('/books');
    }

    public function validateRequest(){
        return request()->validate([
            'title' => 'required',
            'author_id' => 'required'
        ]);
    }
}
