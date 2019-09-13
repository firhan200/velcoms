<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;

class PhotoController extends BaseController
{
    //global variable
    protected $model;
    protected $data;

    public function __construct(){
        //init model
        $this->model = new \App\Models\Photo; //your model name

        //image path
        $this->data['image_path'] = public_path('/images/photos/');
    }

    /* ==================================================== */
    /* ================     REST API   ==================== */
    /* ==================================================== */
    public function index(Request $request){
        $response = ['results' => [], 'total_results'=>0, 'page' => 1, 'total_page' => 0];
        
        try{
        	//init variabels & default value
        	$gallery_id = 0;
        	$keyword = '';
        	$page = 1;
        	$skip = 0;
        	$take = 10;
            $total_page = 0;
            $order_by = 'id'; //default
            $sort = 'desc'; //default

            //gallery id
        	if($request->input('gallery_id')!=null){
        		$gallery_id = $request->input('gallery_id');     		
        	}
            $this->data['gallery_id'] = $gallery_id;

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
            			where('photos.is_deleted', 0)->
            			where(function($query){
			                $query->
			                where('photos.title', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('photos.description', 'LIKE', "%".$this->data['keyword']."%")->
			                orWhere('galleries.title', 'LIKE', "%".$this->data['keyword']."%");
                        })->
                        where(function($query){
                            if($this->data['gallery_id']!=0){
                                $query->
			                    where('photos.gallery_id', $this->data['gallery_id']);
                            }
                        })->
                        leftJoin('galleries', 'photos.gallery_id', 'galleries.id')->
                        select('photos.*', 'galleries.id AS gallery_id', 'galleries.title AS gallery_title')->
            			orderBy($order_by, $sort)->
            			skip($skip)->take($take)->
                        get();
                        
            //total results
            $total_results = $this->model->
                        where('photos.is_deleted', 0)->
                        where(function($query){
                            $query->
                            where('photos.title', 'LIKE', "%".$this->data['keyword']."%")->
                            orWhere('photos.description', 'LIKE', "%".$this->data['keyword']."%")->
                            orWhere('galleries.title', 'LIKE', "%".$this->data['keyword']."%");
                        })->
                        where(function($query){
                            if($this->data['gallery_id']!=0){
                                $query->
			                    where('photos.gallery_id', $this->data['gallery_id']);
                            }
                        })->
                        leftJoin('galleries', 'photos.gallery_id', 'galleries.id')->
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
            //init model
            $model = $this->model;

            //check thumbnail image
            if($request->input('image_thumbnail_name')!=null){
                $image_thumbnail = $this->_convertBase64Image($request->input('image_thumbnail_name'));
                //get filename
                $thumbnail_name = $image_thumbnail['name'];
                //save
                File::put($this->data['image_path'].$thumbnail_name, base64_decode($image_thumbnail['image']));
                //add to model obj
                $model->image_thumbnail_name = $thumbnail_name;   
            }else{
                $response['errors'][] = 'image thumbnail cannot be empty.';
            }

            //check original image
            if($request->input('image_original_name')!=null){
                $image_original = $this->_convertBase64Image($request->input('image_original_name'));
                //get filename
                $original_name = $image_original['name'];
                //save
                File::put($this->data['image_path'].$original_name, base64_decode($image_original['image']));
                //add to model obj
                $model->image_original_name = $original_name;   
            }else{
                $response['errors'][] = 'image original cannot be empty.';
            }

            //check errors
            if(count($response['errors']) < 1){
                //fill data
                $model->title = $request->input('title');
                $model->gallery_id = $request->input('gallery_id');
                $model->description = $request->input('description');
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

            $modelObj = $model::where('photos.id', $id)->leftJoin('galleries', 'photos.gallery_id', 'galleries.id')->
            select('photos.*', 'galleries.id AS gallery_id', 'galleries.title AS gallery_title')->first();
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
                //check thumbnail image
                if($request->input('image_thumbnail_name')!=null){
                    $image_thumbnail = $this->_convertBase64Image($request->input('image_thumbnail_name'));
                    //get filename
                    $thumbnail_name = $image_thumbnail['name'];
                    //save
                    File::put($this->data['image_path'].$thumbnail_name, base64_decode($image_thumbnail['image']));
                    //add to model obj
                    $modelObj->image_thumbnail_name = $thumbnail_name;   
                }

                //check original image
                if($request->input('image_original_name')!=null){
                    $image_original = $this->_convertBase64Image($request->input('image_original_name'));
                    //get filename
                    $original_name = $image_original['name'];
                    //save
                    File::put($this->data['image_path'].$original_name, base64_decode($image_original['image']));
                    //add to model obj
                    $modelObj->image_original_name = $original_name;   
                }

                $modelObj->title = $request->title;
                $modelObj->description = $request->description;
                $modelObj->gallery_id = $request->gallery_id;
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
