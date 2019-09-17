@extends('front.layout')

@section('title', $article->title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-7">
                <img src="{{ asset('images/articles/'.$article->image_cover) }}" class="img-fluid" alt="{{ $article->title }}">
                <br/>
                <br/>
                <h3>{{ $article->title }}</h3>
                {!! $article->body !!}
                <div align="right">
                    <i>posted at: {{ date("H:i, d M Y", strtotime($article->created_at)) }}</i>
                </div>
            </div>
        </div>
    </div>
@endsection