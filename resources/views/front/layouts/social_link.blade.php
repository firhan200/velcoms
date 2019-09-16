<ul class="social-link">
@foreach($social_links as $social_link)
    <li>
        <a href="{{ $social_link->link }}" target="<?php echo $social_link->is_open_newtab ? '_blank' : ''; ?>" title="{{ $social_link->name }}" href="#">
            <img alt="{{ $social_link->name }}" src="{{ asset('images/social_links/'.$social_link->icon_name) }}"/>
        </a>
    </li>
@endforeach
</ul>