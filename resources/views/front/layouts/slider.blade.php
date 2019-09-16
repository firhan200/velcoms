<div id="sliders" class="carousel slide slider" data-ride="carousel">
    <div class="carousel-inner">
        <?php
            $counter = 0;
        ?>
        @foreach($sliders as $slider)
        <a href="{{ $slider->link }}" class="carousel-item <?php echo $counter<=0 ? 'active' : ''; ?>">
            <img src="{{ asset('images/sliders/'.$slider->image_name) }}" class="<?php echo $slider->is_text_shown ? 'dark' : ''; ?> d-block w-100" alt="{{ $slider->title }}">
        
            @if($slider->is_text_shown)
            <div class="carousel-caption d-none d-md-block">
                <h5 class="title">{{ $slider->title }}</h5>
                <p class="sub-title">{{ $slider->sub_title }}</p>
            </div>
            @endif 
            
        </a>
        <?php $counter++; ?>
        @endforeach
    </div>
    @if(count($sliders) > 1)
        <a class="carousel-control-prev" href="#sliders" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#sliders" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    @endif
</div>