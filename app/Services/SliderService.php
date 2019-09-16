<?php
namespace App\Services;

class SliderService{
    //init model
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Slider;
    }

    public function render(){
        //get active social link
        $this->data['sliders'] = $this->model::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();

        return view('front.layouts.slider', $this->data);
    }
}