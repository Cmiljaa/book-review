<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Book::factory(33)->create()->each(function($book){
            $numReviews = random_int(5, 30);

            //factory method expects no arguments (or a single argument to customize the factory state), and bcs of that we need to use count
            Review::factory() -> count($numReviews) -> good() -> for($book) -> create();
        });

        Book::factory(33)->create()->each(function($book){
            $numReviews = random_int(5, 30);

            //factory method expects no arguments (or a single argument to customize the factory state), and bcs of that we need to use count
            Review::factory() -> count($numReviews) -> avg() -> for($book) -> create();
        });

        Book::factory(34)->create()->each(function($book){
            $numReviews = random_int(5, 30);

            //factory method expects no arguments (or a single argument to customize the factory state), and bcs of that we need to use count
            Review::factory() -> count($numReviews) -> bad() -> for($book) -> create();
        });
    }
}
