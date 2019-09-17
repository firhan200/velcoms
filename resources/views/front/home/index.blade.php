@extends('front.layout')

@section('title', 'Velcoms')

@section('content')
    @inject('slider', '\App\Services\SliderService')
    @inject('article', '\App\Services\ArticleService')
    @inject('photo', '\App\Services\PhotoService')

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

    <br/>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div align="center">
                    <h5>Latest Photos</h5>
                </div>
                {!! $photo->renderLatest() !!}
            </div>
        </div>
    </div>

    <br/>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div align="center">
                    <h5>Contact us</h5>
                </div>
                <form id="contact-us-form">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="input your name..."/>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" placeholder="input your email..."/>
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control" placeholder="input your message..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>         
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection