<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_checked_out(){
        //$user->checkout($book);
        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();

        //A book can be checked out
        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function test_a_book_can_be_returned(){
        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();
        $book->checkout($user);

        //A book can be checked out
        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertNotNull(Reservation::first()->checked_in_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    public function test_if_not_checked_out_exception_is_thrown(){
        $this->expectException(Exception::class);

        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();
        $book->checkin($user);
    }

    public function test_a_user_can_check_out_a_book_twice(){
        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();
        $book->checkout($user);
        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_out_at);

        $book->checkin($user);
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_in_at);
    }
}
