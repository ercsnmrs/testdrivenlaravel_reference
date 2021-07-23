<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_author_id_recorded()
    {
        Book::create([
            'title' => 'Cool Title',
            'author_id' => 1,

        ]);

        $this->assertCount(1, Book::all());
    }
}
