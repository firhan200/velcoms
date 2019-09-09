<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use UserTypes;
use JWTAuth;
use Config;

class DashboardController extends Controller
{
    public function __construct(){
        //set jwt user
        Config::set('jwt.user', \App\Models\Administrator::class);
        Config::set('auth.providers', ['users' => [
               'driver' => 'eloquent',
               'model' => \App\Models\Administrator::class,
           ]]);
   }

    //get total data on tables
    public function getTotal(Request $request){
        //init response
        $response = ['total' => 0, 'is_valid' => false, 'message' => ''];

        //get input params
        $table = $request->input('table');
        $is_active = $request->input('is_active');
        $is_deleted = $request->input('is_deleted');

        try{
            //globalize var
            $this->data['table'] = $table;

            //normalize
            $is_active = $is_active=='true' ? 1 : 0;
            $is_deleted = $is_deleted=='true' ? 1 : 0;

            //get user
            $user = JWTAuth::parseToken()->authenticate();
            $this->data['user'] = $user;

            $total = 0;

            //start query
            $total = DB::table($table)->
                where('is_active', $is_active)->
                where('is_deleted', $is_deleted)->
                count();
            
            $response['total'] = $total;
            $response['is_valid'] = true;
        }catch(\Exception $e){
            //error
            $response['message'] = $e->getMessage();
        }
        
        //return data
        return $response;
    }
}
