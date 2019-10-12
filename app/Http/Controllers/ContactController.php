<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AdminNotification;
use Carbon\Carbon;

class ContactController extends Controller
{
    //create contact API
    public function create(Request $request){
        $response = ['is_success' => false, 'message' => '', 'errors' => [], 'status' => ''];

        try{
            //init model
            $query = new \App\Models\Contact;
            //check if email already send contact on period time
            $sameEmailFeedback = $query::where('email', $request->input('email'))->orderBy('id', 'desc')->first();
            if($sameEmailFeedback != null){
                if($sameEmailFeedback->created_at->gt(Carbon::now()->addMinutes(-1))){
                    //stop here wait for a minute
                    $response['errors'][] = 'Your already give us a message, please wait a minute to send us another message.';
                }
            }
            if(count($response['errors']) < 1){
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
            }else{
                foreach($response['errors'] as $error){
                    $response['status'] .= $error.' ';
                }
            }
        }catch(\Exception $ex){
            //error
            $response['status'] = $ex->getMessage();
        }
        

        //return json
        return response()->json($response);
    }
}
