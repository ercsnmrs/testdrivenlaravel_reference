<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    //alias pf "clear && phpunit --filter"
    //Referesh Database Tearsdown the database after test.

    public function test_can_add_to_library(){
        $this->withoutExceptionHandling();
        $response = $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'Aston Martin'
        ]);
        $response->assertStatus(200);
        $this->assertCount(1, Book::all());
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
        $this->withoutExceptionHandling();

        $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'Aston Martin'
        ]);

        $book = Book::first();

        $this->patch('/books/'.$book->id,[
            'title' => 'Generations of Bi-Turbo',
            'author' => 'Enzo'
        ]);

        $this->assertEquals('Generations of Bi-Turbo', Book::first()->title);
        $this->assertEquals('Enzo', Book::first()->author);
    }
}
