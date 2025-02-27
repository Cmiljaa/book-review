<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;

class Book extends Model
{
    use HasFactory;

    //Retrieving all the associated reviews with the specific book
    public function reviews(){
        return $this -> hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title) : Builder|QueryBuilder{
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null) : Builder|QueryBuilder{
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder{
        return $query -> withAvg([ 'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to) ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query -> WithReviewsCount($from, $to) -> orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query-> WithAvgRating($from, $to)->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder{
        return $query->Popular(now()->subMonth(), now())
        ->HighestRated(now()->subMonth(), now())->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder{
        return $query->Popular(now()->subMonths(6), now())
        ->HighestRated(now()->subMonths(6), now())->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder{
        return $query->HighestRated(now()->subMonth(), now())->Popular(now()->subMonth(), now())
        ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder{
        return $query->HighestRated(now()->subMonths(6), now())->Popular(now()->subMonths(6), now())
        ->minReviews(5);
    }

    protected static function booted()
    {
        static::updated(fn(Book $book) => Cache::forget('book:' . $book->book_id));
        static::deleted(fn(Book $book) => Cache::forget('book:' . $book->book_id));
    }
}