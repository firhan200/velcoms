<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends BaseController
{
    //global variable
    protected $model;
    protected $data;

    public function __construct(){
        //init model
        $this->model = new \App\Models\Contact; //your model name
    }

    /* ==================================================== */
    /* ================     REST API   ==================== */
    /* ==================================================== */
    public function index(Request $request){
        $response = ['results' => [], 'total_results'=>0, 'page' => 1, 'total_page' => 0];
        
        try{
        	//init variabels & default value
        	$keyword = '';
        	$page = 1;
        	$skip = 0;
        	$take = 10;
            $total_page = 0;
            $order_by = 'id'; //default
            $sort = 'desc'; //default

            //take
        	if($request->input('take')!=null){
        		$take = (int)$request->input('take');     		
        	}
            
        	//pagination
        	if($request->input('page')!=null){
        		$page = (int)$request->input('page');     		
        	}
            $skip = ($page-1)*$take;
            
        	//search
        	if($request->input('keyword')!=null){
        		$keyword = $request->input('keyword');     		
        	}
            $this->data['keyword'] = $keyword;

            //order by
        	if($request->input('order_by')!=null){
        		$order_by = $request->input('order_by');     		
            }
            
            //sort
        	if($request->input('sort')!=null){
        		$sort = $request->input('sort');     		
        	}
            
        	//results get query
            $results = $this->model->
            			where('is_deleted', 0)->
            			where(function($query){
			                $query->
			                where('name', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('email', 'LIKE', "%".$this->data['keyword']."%");
                        })->
            			orderBy($order_by, $sort)->
            			skip($skip)->take($take)->
                        get();
                        
            //total results
            $total_results = $this->model->
						where('is_deleted', 0)->
            			where(function($query){
			                $query->
			                where('name', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('email', 'LIKE', "%".$this->data['keyword']."%");
                        })->
                        count();
                        
            //total page
            $total_page = ceil($total_results/$take);

            //on result
            $on_result = $skip + $results->count();
            
            //init responses
            $response = ['results' => $results, 'on_result' => $on_result, 'total_results'=>$total_results, 'page' => $page, 'total_page' => $total_page, 'keyword'=>$this->data['keyword']];
        }catch(\Exception $e){
            $response['status'] = $e->getMessage();
        }       

        return response()->json($response);
    }

    //detail /{id} GET
    public function details($id){
        $response = ['data' => null, 'is_success' => false, 'message' => ''];

        try{
            //init model
            $model = $this->model;

            $modelObj = $model::where('id', $id)->first();
            if($modelObj!=null){
                if(!$modelObj->is_deleted){
                    $response['is_success'] = true;
                    $response['data'] = $modelObj;
                }else{
                    //data is deleted
                    $response['message'] = 'data is deleted';
                    $response['data'] = $modelObj;
                }
            }else{
                $response['message'] = 'data not found';
            }
        }catch(\Exception $ex){
            $response['status'] = $ex->getMessage();
        }
        
        //return json
        return response()->json($response);
    }

    //delete /{id} DELETE
    public function delete($id){
        $response = ['data' => null, 'is_success' => false, 'message' => ''];

        try{
            //init model
            $model = $this->model;

            $modelObj = $model::where('id', $id)->where('is_deleted', 0)->first();
            if($modelObj!=null){
                //update
                $modelObj->is_deleted = 1;
                //save
                $modelObj->save();

                $response['is_success'] = true;
                $response['data'] = $modelObj;
            }else{
                $response['message'] = 'data not found';
            }
        }catch(\Exception $ex){
            $response['status'] = $ex->getMessage();
        }
        
        //return json
        return response()->json($response);
    }
}
