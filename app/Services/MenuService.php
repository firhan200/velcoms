<?php
namespace App\Services;

class MenuService{
    private $data;
    
    public function render(){
        $pageQuery = new \App\Models\Page;
        $this->data['pages'] = $pageQuery::where('is_active', 1)->where('is_deleted', 0)->where('is_show_on_menu', 1)->orderBy('id', 'asc')->get();

        return view('front.layouts.menu', $this->data);
    }
}