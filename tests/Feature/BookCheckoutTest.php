<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_checked_out_by_a_signed_in_user()
    {
        // $this->withoutExceptionHandling();

        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();

        //$this->actingAs($user = \App\Models\User::factory()->create())

        $this->actingAs($user)
            ->post('/checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function test_only_signed_in_user_can_checkout_a_book()
    {
        //$this->withoutExceptionHandling();

        $book = \App\Models\Book::factory()->create();

        $this->post('/checkout/' . $book->id)->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    public function test_only_signed_in_user_can_checkin_a_book()
    {
        //$this->withoutExceptionHandling();

        $book = \App\Models\Book::factory()->create();

        $this->actingAs(\App\Models\User::factory()->create())
            ->post('/checkout/' . $book->id);

        Auth::logout();

        $this->post('/checkin/' . $book->id)
            ->assertRedirect('/login');

        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }


    public function test_only_real_books_can_be_checked_out()
    {
        $this->actingAs($user = \App\Models\User::factory()->create())
            ->post('/checkout/123')
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_a_book_can_be_checked_in_by_a_signed_in_user()
    {
        //$this->withoutExceptionHandling();

        $book = \App\Models\Book::factory()->create();
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)
            ->post('/checkout/' . $book->id);

        // Reservation::create([
        //     'checked_in_at' => now()->subDays(2),
        //     'user_id' => $user->id,
        // ]);

        $this->actingAs($user)
            ->post('/checkin/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    public function test_if_a_book_is_not_checked_out_first_throw_404(){
         // $this->withoutExceptionHandling();

         $book = \App\Models\Book::factory()->create();
         $user = \App\Models\User::factory()->create();

         //$this->actingAs($user = \App\Models\User::factory()->create())

         $this->actingAs($user)
             ->post('/checkin/' . $book->id)
             ->assertStatus(404);

         $this->assertCount(0, Reservation::all());
    }
}
