<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;

class SocialLinkController extends BaseController
{
    //global variable
    protected $model;
    protected $data;

    public function __construct(){
        //init model
        $this->model = new \App\Models\Social_Link; //your model name

        //image path
        $this->data['image_path'] = public_path('/images/social_links/');
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
			                orWhere('link', 'LIKE', "%".$this->data['keyword']."%");
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
			                orWhere('link', 'LIKE', "%".$this->data['keyword']."%");
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

    //create data POST
    public function create(Request $request){
        $response = ['is_success' => false, 'message' => ''];

        try{
            //init model
            $model = $this->model;

            //check image
            if($request->input('icon_name')!=null){
                $icon_name = $this->_convertBase64Image($request->input('icon_name'));
                //get filename
                $file_name = $icon_name['name'];
                //save
                File::put($this->data['image_path'].$file_name, base64_decode($icon_name['image']));
                //add to model obj
                $model->icon_name = $file_name;

                //fill data
                $model->name = $request->input('name');
                $model->link = $request->input('link');
                $model->is_open_newtab = $request->input('is_open_newtab');
                $model->is_active = 1; //default is active
                $model->is_deleted = 0; //default

                //save to db
                $model->save();

                $response['is_success'] = true;
            }else{
                $response['message'] = 'image cannot be empty.';
            }
        }catch(\Exception $ex){
            //error
            $response['status'] = $ex->getMessage();
        }
        

        //return json
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

    //update /{id} PUT
    public function update(Request $request, $id){
        $response = ['data' => null, 'is_success' => false, 'message' => ''];

        try{
            //init model
            $model = $this->model;

            $modelObj = $model::where('id', $id)->where('is_deleted', 0)->first();
            if($modelObj!=null){
                //update
                //check image
                if($request->input('icon_name')!=null){
                    $icon_name = $this->_convertBase64Image($request->input('icon_name'));
                    //get filename
                    $file_name = $icon_name['name'];
                    //save
                    File::put($this->data['image_path'].$file_name, base64_decode($icon_name['image']));
                    //add to model obj
                    $modelObj->icon_name = $file_name;
                }

                $modelObj->name = $request->name;
                $modelObj->link = $request->link;
                $modelObj->is_open_newtab = $request->is_open_newtab;
                $modelObj->is_active = $request->is_active;

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
