<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    //alias pf="clear && phpunit --filter"
    //alias phpunit="./vendor/bin/phpunit"
    //Referesh Database Tearsdown the database after test.

    public function test_can_add_to_library(){
        // $this->withoutExceptionHandling();
        $response = $this->post('/books', $this->data());

        //Doesnt need to get 302 if we are redirecting
        // $response->assertStatus(200);
        //$response->assertOk();

        $book= Book::first();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_validate_title(){
        //Disbale Excpetion Handling
        //$this->withoutExceptionHandling();
        $response = $this->post('/books',[
            'title' => '',
            'author' => 'Aston Martin'
        ]);
        $response->assertSessionHasErrors('title');
    }

    public function test_validate_author(){
        $response = $this->post('/books',
            array_merge($this->data(), ['author_id'=>''])
        );
        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function update_book(){
        // $this->withoutExceptionHandling();

        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(),[
            'title' => 'Generations of Bi-Turbo',
            'author_id' => 'Enzo'
        ]);

        $this->assertEquals('Generations of Bi-Turbo', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    public function test_delete_book(){
        // $this->withoutExceptionHandling();

        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    public function test_author_automatically_added(){
        $this->withoutExceptionHandling();
        $this->post('/books',[
            'title' => 'Cool Book Title',
            'author_id' => 'Aston Martin'
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data(){
        return [
            'title' => 'Cool Book Title',
            'author_id' => 'Aston Martin'
        ];
    }
}

