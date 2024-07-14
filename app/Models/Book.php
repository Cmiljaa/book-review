<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function reviews(){
        return $this -> hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title) : Builder{
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopePopular(Builder $query):Builder{
        return $query->withCount('reviews')->orderBy('reviews_count', 'DESC');
    }

    public function scopeHighestRated(Builder $query):Builder{
        return $query->withCount('reviews')->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'DESC');
    }
}