<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    private $data;
    private $model;

    public function __construct(){
        $this->model = new \App\Models\Page;
    }

    //show page
    public function show($url = null){
        //init default var
        $this->data['message'] = '';
        $this->data['is_success'] = true;

        //get page
        $this->data['page'] = $this->model->where('url', $url)->where('is_deleted', 0)->first();
        if($this->data['page']!=null){
            //page found, check validation
            if(!$this->data['page']->is_active){
                //page inactive
                $this->data['message'] = 'Page is in-active';
                $this->data['is_success'] = false;
            }
        }else{
            //page doest exist
            $this->data['message'] = 'Page not found!';
            $this->data['is_success'] = false;
        }

        return view('front.pages.show', $this->data);
    }
}
