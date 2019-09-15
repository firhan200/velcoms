<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AdminNotification;

class ContactController extends Controller
{
    //create contact API
    public function create(Request $request){
        $response = ['is_success' => false, 'message' => ''];

        try{
            //init model
            $model = new \App\Models\Contact;

            //fill data
            $model->name = $request->input('name');
            $model->email = $request->input('email');
            $model->message = $request->input('message');

            $model->is_active = 1; //default is active
            $model->is_deleted = 0; //default

            //save to db
            $model->save();

            //create notification to admin
            AdminNotification::create(
                //message
                $model->name." send new contact: ".
                (strlen($model->message) > 50 ? substr($model->message, 0, 50)."..." : $model->message),
                //link
                "/cms/contacts?show=detail&id=".$model->id,
                //is vue link
                true
            );

            $response['is_success'] = true;
        }catch(\Exception $ex){
            //error
            $response['status'] = $ex->getMessage();
        }
        

        //return json
        return response()->json($response);
    }
}
