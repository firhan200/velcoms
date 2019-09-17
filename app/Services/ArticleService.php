<?php
namespace App\Services;

class ArticleService{
    //init model
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Article;
    }

    public function renderLatest($take = 3){
        //get active social link
        $this->data['articles'] = $this->model::where('articles.is_active', 1)->
            where('articles.is_deleted', 0)->orderBy('id', 'desc')->
            take($take)->
            leftJoin('article_categories', 'articles.article_category_id', 'article_categories.id')->
            select('articles.*', 'article_categories.name as article_category_name')->
            get();

        return view('front.layouts.latest_articles', $this->data);
    }
}