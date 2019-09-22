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
        //get latest articles
        $this->data['articles'] = $this->model::where('articles.is_active', 1)->
            where('articles.is_deleted', 0)->orderBy('id', 'desc')->
            take($take)->
            leftJoin('article_categories', 'articles.article_category_id', 'article_categories.id')->
            select('articles.*', 'article_categories.name as article_category_name')->
            get();

        return view('front.layouts.latest_articles', $this->data);
    }

    public function renderRelatedArticles($currentArticle = null, $take = 3){
        //get related articles by category id, order by latest update
        $this->data['articles'] = $this->model::where('articles.is_active', 1)->
            where('articles.is_deleted', 0)->orderBy('id', 'desc')->
            where('articles.article_category_id', $currentArticle->article_category_id)->
            where('articles.id', '!=', $currentArticle->id)->
            take($take)->
            leftJoin('article_categories', 'articles.article_category_id', 'article_categories.id')->
            select('articles.*', 'article_categories.name as article_category_name')->
            orderBy('articles.updated_at', 'desc')->
            get();

        return view('front.layouts.related_articles', $this->data);
    }
}