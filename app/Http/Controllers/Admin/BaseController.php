<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function _convertBase64Image($image){
        //get image type
        $imageList = explode(';', $image);
        $imageListData = explode(':',$imageList[0]);
        $imageType = explode('/', $imageListData[1])[1];
        //filename
        $fileName = gmdate("d-m-y-H-i-s", time()).'.'.$imageType;
        //normalize base 64 string
        $image = str_replace('data:image/'.$imageType.';base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        return ['name' => $fileName, 'image' => $image];
    }
}