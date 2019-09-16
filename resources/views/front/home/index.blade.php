@extends('front.layout')

@section('title', 'Velcoms')

@section('content')
    @inject('slider', '\App\Services\SliderService')
    @inject('article', '\App\Services\ArticleService')

    {!! $slider->render() !!}

    <br/>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div align="center">
                    <h5>Latest Articles</h5>
                </div>
                {!! $article->renderLatest() !!}
            </div>
        </div>
    </div>
@endsection