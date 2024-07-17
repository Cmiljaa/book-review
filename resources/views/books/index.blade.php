@extends('layouts.app')
@section('content')

<form action="{{route('books.index')}}" method="GET" class="mb-4 flex items-center space-x-2">
    <input type="text" class="input h-10" name="title" placeholder="Search by title" value="{{request('title')}}">
    <button type="submit" class="btn">Search</button>
    <a href="{{route('books.index')}}" class="btn h-10">Clear</a>
</form>

    <ul>
    @forelse ($books as $book)
        <li class="mb-4">
        <div class="book-item">
            <div
            class="flex flex-wrap items-center justify-between">
            <div class="w-full flex-grow sm:w-auto">
                <a href="{{route('books.show', $book)}}" class="book-title">{{$book->title}}</a>
                <span class="book-author">by {{$book->author}}</span>
            </div>
            <div>
                <div class="book-rating">
                    {{number_format($book->reviews_avg_rating, 1)}}
                </div>
                <div class="book-review-count">
                out of  {{$book->reviews_count}}
                </div>
            </div>
            </div>
        </div>
        </li>
    @empty
        <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
        </div>
        </li>
    @endforelse
    </ul>

@endsection