<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    // public function test_dob_is_nullable()
    // {
    //    Author::firstOrCreate([
    //        'name' => 'John Doe'
    //    ]);

    //    $this->assertCount(1, Author::all());
    // }

    public function test_only_name_required()
    {
       Author::firstOrCreate([
           'name' => 'John Doe'
       ]);

       $this->assertCount(1, Author::all());
    }
}
