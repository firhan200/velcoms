<div class="row">
@foreach($articles as $article)
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <img src="{{ asset('images/articles/'.$article->image_cover) }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $article->title }}</h5>
                <p class="card-text">
                    @if(strlen($article->slug) > 50)
                        {{ substr($article->slug, 0, 50) }}...
                    @else
                        {{ $article->slug }}
                    @endif
                </p>
            </div>
        </div>
    </div>
@endforeach
</div>