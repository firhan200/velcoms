<div class="row latest-photos">
@foreach($photos as $photo)
    <div class="col-sm-12 col-md-4 col-lg-3">
        <div class="card">
            <img src="{{ asset('images/photos/'.$photo->image_thumbnail_name) }}" class="card-img-top" alt="...">          
        </div>
    </div>
@endforeach
</div>