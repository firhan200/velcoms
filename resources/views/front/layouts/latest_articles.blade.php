<div class="row articles">
@foreach($articles as $article)
    <div class="col-sm-12 col-md-6 col-lg-4">
        <a href="{{ url('/articles/'.$article->url) }}">
            <div class="card">
                <img src="{{ asset('images/articles/'.$article->image_cover) }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $article->title }}</h5>
                    <div class="category badge badge-primary">{{ $article->article_category_name }}</div>
                    <p class="card-text">
                        @if(strlen($article->slug) > 50)
                            {{ substr($article->slug, 0, 50) }}...
                        @else
                            {{ $article->slug }}
                        @endif
                    </p>
                </div>
            </div>
        </a>
    </div>
@endforeach
</div>