<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Article;
    }

    //article list
    public function index(){
        return view('front.articles.index');
    }

    //detail article
    public function detail($url){
        //get article by url
        $article = $this->model->where('url', $url)->first();

        //set to global var
        $this->data['article'] = $article;

        return view('front.articles.detail', $this->data);
    }
}
