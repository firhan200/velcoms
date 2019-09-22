@extends('front.layout')

@section('title', $article!=null ? $article->title : "not found")

@section('content')
    @if($article!=null)
        @inject('articleService', '\App\Services\ArticleService')

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-8">
                    <img src="{{ asset('images/articles/'.$article->image_cover) }}" class="img-fluid" alt="{{ $article->title }}">
                    <br/>
                    <br/>
                    <h3>{{ $article->title }}</h3>
                    {!! $article->body !!}
                    <div align="right">
                        <i>posted at: {{ date("H:i, d M Y", strtotime($article->created_at)) }}</i>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                    {!! $articleService->renderRelatedArticles($article, 3) !!}
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-sm-12" align="center">
                    ARTICLE NOT FOUND!
                </div>
            </div>
        </div>
    @endif
@endsection