<?php

namespace Tests\Feature;

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
        $response = $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'Aston Martin'
        ]);

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
        $response = $this->post('/books',[
            'title' => 'Cars',
            'author' => ''
        ]);
        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function update_book(){
        // $this->withoutExceptionHandling();

        $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'Aston Martin'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(),[
            'title' => 'Generations of Bi-Turbo',
            'author' => 'Enzo'
        ]);

        $this->assertEquals('Generations of Bi-Turbo', Book::first()->title);
        $this->assertEquals('Enzo', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    public function test_delete_book(){
        // $this->withoutExceptionHandling();

        $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'Aston Martin'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}

