<li class="nav-item active">
    <a class="nav-link" href="{{ url('/') }}">HOME</a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="#">GALLERY</a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="{{ url('/articles') }}">ARTICLES</a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="#">CONTACT US</a>
</li>

@foreach($pages as $page)
<li class="nav-item active">
    <a class="nav-link" href="{{ url('/pages/'.$page->url) }}">{{ $page->menu_name }}</a>
</li>
@endforeach