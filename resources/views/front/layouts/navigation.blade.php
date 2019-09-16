@inject('menu', '\App\Services\MenuService')
@inject('social_link', '\App\Services\SocialLinkService')

<nav class="navbar navbar-expand-lg navbar-light bg-light container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            {!! $menu->render() !!}   
        </ul>
        <ul class="navbar-nav ml-auto">
            {!! $social_link->render() !!}   
        </ul>
    </div>
</nav>