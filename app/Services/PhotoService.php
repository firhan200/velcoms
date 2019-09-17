<?php
namespace App\Services;

class PhotoService{
    //init model
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Photo;
    }

    public function renderLatest($take = 4){
        //get active social link
        $this->data['photos'] = $this->model::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        return view('front.layouts.latest_photos', $this->data);
    }
}