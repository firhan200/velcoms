<?php
namespace App\Services;

class SocialLinkService{
    //init model
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Social_Link;
    }

    public function render(){
        //get active social link
        $this->data['social_links'] = $this->model::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();

        return view('front.layouts.social_link', $this->data);
    }
}