<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends BaseController
{
    //global variable
    protected $model;
    protected $data;

    public function __construct(){
        //init model
        $this->model = new \App\Models\Page; //your model name
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
			                where('title', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('url', 'LIKE', "%".$this->data['keyword']."%");
                        })->
            			orderBy($order_by, $sort)->
            			skip($skip)->take($take)->
                        get();
                        
            //total results
            $total_results = $this->model->
						where('is_deleted', 0)->
            			where(function($query){
			                $query->
			                where('title', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('url', 'LIKE', "%".$this->data['keyword']."%");
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
        $response = ['is_success' => false, 'message' => '', 'errors' => []];

        try{
            //validate
            $objQuery = new \App\Models\Page;
            $titleExist = $objQuery::where('title', $request->title)->first();
            if($titleExist!=null){
                $response['errors'][] = "title already taken.";
            }

            $urlExist = $objQuery::where('url', $request->url)->first();
            if($urlExist!=null){
                $response['errors'][] = "url already taken.";
            }

            if(count($response['errors']) < 1){
                //init model
                $model = $this->model;

                //fill data
                $model->title = $request->input('title');
                $model->url = urlencode($request->input('url'));
                $model->body = $request->input('body');
                $model->is_active = 1; //default is active
                $model->is_deleted = 0; //default

                //save to db
                $model->save();

                $response['is_success'] = true;
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
                    $response['url'] = urldecode($modelObj->url);

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
        $response = ['data' => null, 'is_success' => false, 'message' => '', 'errors' => []];

        try{
            //init model
            $model = $this->model;

            $modelObj = $model::where('id', $id)->where('is_deleted', 0)->first();
            if($modelObj!=null){
                //validate
                $objQuery = new \App\Models\Page;
                $titleExist = $objQuery::where('title', $request->title)->where('id', '!=', $modelObj->id)->first();
                if($titleExist!=null){
                    $response['errors'][] = "title already taken.";
                }

                $urlExist = $objQuery::where('url', $request->url)->where('id', '!=', $modelObj->id)->first();
                if($urlExist!=null){
                    $response['errors'][] = "url already taken.";
                }

                if(count($response['errors']) < 1){
                    //update
                    $modelObj->title = $request->title;
                    $modelObj->url = urlencode($request->url);
                    $modelObj->body = $request->body;
                    $modelObj->is_active = $request->is_active;

                    //save
                    $modelObj->save();

                    $response['is_success'] = true;
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
