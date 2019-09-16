<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //landing page website
    public function index(){
        return view('front.home.index');
    }
}