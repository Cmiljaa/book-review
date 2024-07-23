<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory;

    public function Book(){
        return $this -> belongsTo(Book::class);
    }

    protected $fillable = ['review', 'rating'];

    protected static function booted()
    {
        //This resets the cache if we update, delete, or create a review, it will be updated automatically
        //We here just refresh the cache
        static::updated(fn(Review $review) => Cache::forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => Cache::forget('book:' . $review->book_id));
        static::created(fn(Review $review) => Cache::forget('book:' . $review->book_id));
    }
}