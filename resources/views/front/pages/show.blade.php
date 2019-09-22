@extends('front.layout')

@section('title', $page->title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @if($is_success)
                    {!! $page->body !!}
                @else
                    {{ $message }}
                @endif
            </div>
        </div>
    </div>
@endsection